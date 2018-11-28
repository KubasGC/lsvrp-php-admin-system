<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

class GroupLibrary 
{

    public static $GroupTypes = [
        1 => "Police Department",
        2 => "Fire Department",
        3 => "Weazel News",
        4 => "Przestępcza",
        5 => "Rząd",
        6 => "Warsztat samochodowy",
        7 => "Gastronomia",
        8 => "Projekt IC",
        9 => "Rodzina",
        10 => "Sieć GSM"
    ];

	public static function GetGroupData($groupId)
    {
        $groupData = DB::table("lsvrp_groups")->where("Id", "=", $groupId)->first();
        if ($groupData == null) return null;

        $groupData->HexColor = sprintf("#%02x%02x%02x", $groupData->ColorR, $groupData->ColorG, $groupData->ColorB);
        $groupData->TypeName = self::GetGroupTypeName($groupData->Type);

        return $groupData;
    }

    public static function GetGroupTypeName($groupType)
    {
        if (array_key_exists($groupType, self::$GroupTypes))
        {
            return self::$GroupTypes[$groupType];
        }
        return "Nieznany";
    }

    public static function GetGroupOrders($groupId)
    {
        return DB::table("lsvrp_orders")->where("GroupId", "=", $groupId)->get();
    }

    public static function GetGroupPermissions()
    {
        $output = [];
        $groupPermissions = DB::table("lsvrp_groups_groupperms")->get();
        foreach ($groupPermissions as $groupPermission)
        {
            $output[$groupPermission->permName] = ["desc" => $groupPermission->permContent];
        }
        return $output;
    }

    public static function GetGroupMembers($groupId)
    {
        $output = DB::table("lsvrp_characters")
            ->leftJoin("lsvrp_characters_groups", "lsvrp_characters.Id", "=", "lsvrp_characters_groups.CharacterID")
            ->leftJoin("lsvrpcore_members", "lsvrp_characters.MemberID", "=", "lsvrpcore_members.member_id")
            ->leftJoin("lsvrp_groups_ranks", "lsvrp_groups_ranks.Id", "=", "lsvrp_characters_groups.RankID")
            ->where("lsvrp_characters_groups.GroupID", "=", $groupId)
            ->orderBy("lsvrp_groups_ranks.Id", "asc")
            ->orderBy("lsvrp_characters.Name", "asc")
            ->orderBy("lsvrp_characters.Lastname", "asc")
            ->get(["lsvrp_characters.Name", "lsvrp_characters.Lastname", "lsvrpcore_members.name as Globalname",
                "lsvrp_characters.MemberID", "lsvrp_characters.Id as CharID", "lsvrp_groups_ranks.Name as Rankname",
                "lsvrp_groups_ranks.Permissions as RankPermissions", "lsvrp_groups_ranks.Id as RankID",
                "lsvrp_characters_groups.CreatedAt"]);

        foreach ($output as $key => $value)
        {
            $output[$key]->DecodedRankPermissions = json_decode($value->RankPermissions);
            if ($output[$key]->DecodedRankPermissions == null) $output[$key]->DecodedRankPermissions = [];
        }

        return $output;
    }
}