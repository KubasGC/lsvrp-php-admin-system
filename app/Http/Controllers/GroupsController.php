<?php

namespace App\Http\Controllers;

use App\Libraries\ItemLibrary;
use App\Libraries\MiscLibrary;
use App\Libraries\SocketLibrary;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\GroupLibrary;
use Jenssegers\Date\Date;

class GroupsController extends Controller
{
    public function __construct()
	{
		
	}
	
	public function ShowGroups($groupId = null)
	{
		if ($groupId != null) return $this->ShowGroup($groupId);
		$groupsData = DB::table("lsvrp_groups")->orderBy("Id")->get();
		for($i = 0; $i < count($groupsData); $i++)
		{
		    $groupsData[$i]->TypeName = GroupLibrary::GetGroupTypeName($groupsData[$i]->Type);
			$groupsData[$i]->employees = DB::table("lsvrp_characters_groups")->where(["GroupID" => $groupsData[$i]->Id])->count();
		}
		return view("groups.list", ["groupsData" => $groupsData]);
	}
	
	protected function ShowGroup($groupId)
	{
	    Date::setLocale("pl");
		$groupData = GroupLibrary::GetGroupData($groupId);
		if ($groupData == null) return view("base.404");

		$groupData->DecodedPermissions = json_decode($groupData->Permissions, true);
		if ($groupData->DecodedPermissions == null) $groupData->DecodedPermissions = [];
        $groupPermissions = GroupLibrary::GetGroupPermissions();
        foreach ($groupPermissions as $key => $value)
        {
            $groupPermissions[$key]["status"] = in_array($key, $groupData->DecodedPermissions) ? 1 : 0;
        }

        $groupMembers = GroupLibrary::GetGroupMembers($groupData->Id);

		return view("groups.show", ["groupData" => $groupData, "groupPermissions" => $groupPermissions,
            "groupTypes" => GroupLibrary::$GroupTypes, "groupMembers" => $groupMembers, "itemTypes" => ItemLibrary::GetItemTypes()]);
	}

	protected function DoSaveData($groupId, Request $request)
    {
        $groupData = GroupLibrary::GetGroupData($groupId);
        if ($groupData == null) return view("base.404");

        $request->validate([
            "groupName" => "required|max:32",
            "groupTag" => "required|min:2|max:4",
            "groupType" => "required|integer",
            "groupColor" => "required",
            "groupBank" => "required|integer",
            "groupDonation" => "required|integer|between:0,1200"
        ]);

        $rgbColor = MiscLibrary::Hex2Rgb($request->input("groupColor"));

        DB::table("lsvrp_groups")->where([ "Id" => $groupData->Id ])->update([
            "Code" => $request->input("groupTag"),
            "Name" => $request->input("groupName"),
            "Type" => $request->input("groupType"),
            "Cash" => $request->input("groupBank"),
            "Donation" => $request->input("groupDonation"),
            "ColorR" => $rgbColor[0],
            "ColorG" => $rgbColor[1],
            "ColorB" => $rgbColor[2]
        ]);

        $socketInfo = SocketLibrary::Send(SocketLibrary::TypeReloadGroup, $groupData->Id);

        $message = "Dane grupy zostały zaktualizowane pomyślnie. ";
        if ($socketInfo) $message .= "Przeładowano dane na serwerze.";
        else $message .= "Nie udało się przeładować danych na serwerze";
        return redirect()->back()->with("toast-info", $message);
    }

    protected function DoSavePerms($groupId, Request $request)
    {
        $groupData = GroupLibrary::GetGroupData($groupId);
        if ($groupData == null) return view("base.404");

        $permissions = array();
        foreach (GroupLibrary::GetGroupPermissions() as $key => $value)
        {
            if ($request->input("perm_{$key}") == "true")
            {
                $permissions[] = $key;
            }
        }

        DB::table("lsvrp_groups")->where(["Id" => $groupData->Id])->update([
           "Permissions" => json_encode($permissions)
        ]);

        SocketLibrary::Send(SocketLibrary::TypeReloadGroup, $groupData->Id);
        return redirect()->back()->with("toast-info", "Uprawnienia zostały zaktualizowane pomyślnie. Przeładowano dane na serwerze.");


    }
}
