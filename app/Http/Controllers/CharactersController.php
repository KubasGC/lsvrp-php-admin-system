<?php

namespace App\Http\Controllers;


use App\Libraries\CharacterLibrary;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class CharactersController extends Controller
{
    public function __construct()
    {

    }

    public function ShowSearch()
    {
        return view("characters.search");
    }

    public function ShowCharacter($charId)
    {
        $charData = CharacterLibrary::GetCharData($charId);
        if ($charData == null) return view("base.404");

        return view("characters.show", ["charData" => $charData]);
    }

    public function OnlineTab()
    {
        Date::setLocale("pl");

        $charactersOnline = DB::table("lsvrp_characters")
            ->leftJoin("lsvrpcore_members", "lsvrpcore_members.member_id", "=", "lsvrp_characters.MemberID")
            ->where(["lsvrp_characters.InGame" => 1])
            ->orderBy("lsvrp_characters.LastLogin", "desc")
            ->get(["lsvrp_characters.Id as CharId", "lsvrp_characters.Name", "lsvrp_characters.Lastname", "lsvrpcore_members.name as GlobalName", "lsvrp_characters.LastLogin"]);

        foreach ($charactersOnline as $key => $value)
        {
            $charactersOnline[$key]->SumTime = CharacterLibrary::SecondsToHumanDiff(time() - $value->LastLogin);
            $charactersOnline[$key]->LastLogged = Date::createFromTimestamp($value->LastLogin)->format("l, j F Y H:i");

        }

        return view("characters.online", ["online" => $charactersOnline]);
    }
}
