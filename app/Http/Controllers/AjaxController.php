<?php

namespace App\Http\Controllers;

use App\Libraries\CharacterLibrary;
use App\Libraries\GroupLibrary;
use App\Libraries\ItemLibrary;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class AjaxController extends Controller
{
    public function __construct()
    {

    }

    public function LastChanges(Request $request)
    {
        Date::setLocale("PL_pl");
        $data = DB::table("lsvrp_lastchanges")->orderBy("Id", "desc")
            ->leftJoin("lsvrpcore_members", "lsvrp_lastchanges.Admin", "=", "lsvrpcore_members.member_id")
            ->limit(5)
            ->get(["lsvrp_lastchanges.Message", "lsvrpcore_members.name as AdminName", "lsvrpcore_members.pp_gravatar as Gravatar", "lsvrp_lastchanges.Timestamp", "lsvrp_lastchanges.Title"]);
        foreach ($data as $key => $value)
        {
            if ($value->Gravatar != null)
            {
                $data[$key]->Gravatar = "https://secure.gravatar.com/avatar/".md5($value->Gravatar)."?size=64";
            }
            $data[$key]->Date = Date::createFromTimestamp($value->Timestamp)->diffForHumans();
        }
        return response()->json($data)->setCallback($request->input('callback'));;
    }

    public function SearchChar(Request $request)
    {
        $response = [
          "status" => false
        ];

        if ($request->has("search"))
        {
            $data = null;
            if (ctype_digit($request->input("search")))
            {
                $data = DB::table("lsvrp_characters")
                    ->leftJoin("lsvrpcore_members", "lsvrpcore_members.member_id", "=", "lsvrp_characters.MemberID")
                    ->where("lsvrp_characters.Id", "=", intval($request->input("search")))
                    ->orWhere("lsvrpcore_members.member_id", "=", intval($request->input("search")));
            }
            else
            {
                $data = DB::table("lsvrp_characters")
                    ->leftJoin("lsvrpcore_members", "lsvrpcore_members.member_id", "=", "lsvrp_characters.MemberID")
                    ->whereRaw("CONCAT(lsvrp_characters.Id, ' ', lsvrp_characters.Name, ' ', lsvrp_characters.Lastname , ' ', lsvrpcore_members.name, ' ', lsvrp_characters.ShortDNA) LIKE ?",
                        [ "%".$request->input("search")."%" ]);
            }


            $response["status"] = true;
            $response["count"] = $data->count();
            $response["data"] = [];
            foreach ($data->get(["lsvrp_characters.Id", "lsvrp_characters.Name", "lsvrp_characters.Lastname",
                "lsvrpcore_members.name as Globalname", "lsvrp_characters.MemberID"]) as $user)
            {
                $response["data"][] = $user;
            }
        }

        return response()->json($response);
    }

    public function GroupAddOrder(Request $request)
    {
        DB::table("lsvrp_orders")->insert([
            "GroupId" => $request->input("groupId"),
            "Name" => $request->input("name"),
            "Price" => $request->input("price"),
            "Type" => $request->input("type"),
            "Value1" => $request->input("val1"),
            "Value2" => $request->input("val2"),
            "Value3" => $request->input("val3"),
            "Flag" => $request->input("flag"),
            "OrderedSum" => 0
        ]);
        $response = [
          "status" => true
        ];
        return response()->json($response);
    }

    public function GroupGetOrders($groupid)
    {
        $orders = GroupLibrary::GetGroupOrders($groupid);
        foreach ($orders as $key => $value)
        {
            $orders[$key]->TypeName = ItemLibrary::GetItemTypeName($orders[$key]->Type);
        }
        return response()->json($orders);
    }

    public function GroupDeleteOrder($orderid)
    {
        DB::table("lsvrp_orders")->where("Id", "=", $orderid)->delete();
        $response = [
            "status" => true
        ];
        return response()->json($response);
    }

    public function GroupSaveOrders($groupid, Request $request)
    {
        $orders = GroupLibrary::GetGroupOrders($groupid);
        $response = [
            "status" => true
        ];
        foreach ($orders as $order)
        {
            $name = $request->input("Name_".$order->Id);
            $val1 = $request->input("Val1_".$order->Id);
            $val2 = $request->input("Val2_".$order->Id);
            $val3 = $request->input("Val3_".$order->Id);
            $price = $request->input("Price_".$order->Id);
            $flag = $request->input("Flag_".$order->Id);

            DB::table("lsvrp_orders")->where("Id", "=", $order->Id)->update([
                "Name" => $name,
                "Price" => $price,
                "Value1" => $val1,
                "Value2" => $val2,
                "Value3" => $val3,
                "Flag" => $flag == "on" ? 1 : 0
            ]);

        }
        return response()->json($response);
    }

    public function CharLoadData($charId)
    {
        $response = [
            "charData" => CharacterLibrary::GetCharData($charId),
            "charPenalties" => CharacterLibrary::GetCharPenalties($charId)
        ];
        return response()->json($response);
    }

    public function GroupPenalty(Request $request)
    {
        if ($request->has("penaltyType") && $request->has("charId"))
        {
            $penaltyType = intval($request->input("penaltyType"));
            $charId = intval($request->input("charId"));

            if ($penaltyType == 1) // blokada postaci
            {
                DB::table("lsvrp_penalties")->insert([
                    "AdminID" => Auth::user()->getGlobalId(),
                    "TargetID" => $charId,
                    "TargetGlobalID" => 0,
                    "Type" => 2,
                    "Reason" => $request->input("penaltyDesc"),
                    "TimeStamp" => time(),
                    "Expired" => 0
                ]);
                DB::table("lsvrp_characters")->where("Id", "=", $charId)->update([
                    "Blocked" => 1
                ]);

                $response = ["status" => true];
                return response()->json($response);
            }
            else if ($penaltyType == 2) // anulowanie blokady postaci
            {
                DB::table("lsvrp_characters")->where("Id", "=", $charId)->update([
                    "Blocked" => 0
                ]);

                $data = DB::table("lsvrp_penalties")->where([
                    "TargetID" => $charId,
                    "Type" => 2
                ])->orderBy("Id", "desc");

                if ($data->count() > 0)
                {
                    $data = $data->first();
                    DB::table("lsvrp_penalties")->where("Id", "=", $data->Id)->update(["Expired" => -1]);
                }

                $response = ["status" => true];
                return response()->json($response);
            }
        }
        else
        {
            $response = ["status" => false];
            return response()->json($response);
        }
    }

    public function ShopsLoadData()
    {

        $response = [];

        $shops = DB::table("lsvrp_247_shops")->get();

        foreach ($shops as $sKey => $sValue)
        {
            $sValue->Products = DB::table("lsvrp_247_products")->where("ShopID", "=", $sValue->Id)->get();

            foreach ($sValue->Products as $pKey => $pValue)
            {
                $sValue->Products[$pKey]->TypeName = ItemLibrary::GetItemTypeName($pValue->Type);
            }

            $response[] = $sValue;
        }

        $response = [
            "shopsData" => $response,
            "itemTypes" => ItemLibrary::GetItemTypes()
        ];

        return  response()->json($response);

    }

    public function ShopRemoveProduct($productId)
    {
        DB::table("lsvrp_247_products")->where("Id", "=", $productId)->delete();
        return response()->json(["success" => true]);
    }

    public function ShopAddProduct(Request $request)
    {
        DB::table("lsvrp_247_products")->insert([
            "Name" => $request->input("name"),
            "Type" => intval($request->input("type")),
            "Value1" => intval($request->input("val1")),
            "Value2" => intval($request->input("val2")),
            "Value3" => $request->input("val3"),
            "Price" => intval($request->input("price")),
            "ShopID" => intval($request->input("shopId"))
        ]);

        return response()->json(["status" => true]);
    }
}
