<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class CharacterLibrary
{

    public static $m_penaltyTypes = [
        0 => "Nieznana",
        1 => "Kick",
        2 => "Blokada postaci",
        3 => "Ostrzeżenie",
        4 => "Ban",
        5 => "Admin Jail",
        6 => "Character Kill",
        7 => "Blokada prowadzenia pojazdów",
        8 => "Blokada czatu OOC",
        9 => "vPoints",
        10 => "Blokada prędkości"
    ];

    public static function GetCharData($charId)
    {
        $charData = DB::table("lsvrp_characters")->where("Id", "=", $charId)->first();
        if ($charData == null) return null;

        return $charData;
    }

    public static function SecondsToHumanDiff($sec)
    {
        $hours = floor($sec / 3600);
        $minutes = floor(($sec / 60) % 60);
        $seconds = $sec % 60;

        return sprintf("%02dh %02dm %02ds", $hours, $minutes, $seconds);

    }

    public static function GetCharPenalties($charId)
    {
        Date::setLocale("pl");
        $penalties = DB::table("lsvrp_penalties")->where("TargetID", "=", $charId)
        ->leftJoin("lsvrpcore_members", "lsvrpcore_members.member_id", "=", "lsvrp_penalties.AdminID");
        if ($penalties->count() == 0) return null;

        $data = $penalties->get(["AdminID", "TargetID", "Type", "Reason", "TimeStamp", "Expired", "lsvrpcore_members.name as AdminName"]);

        foreach ($data as $key => $value)
        {
            if ($value->AdminID == 0)
            {
                $data[$key]->AdminName = "System";
            }

            $data[$key]->TypeName = self::$m_penaltyTypes[$value->Type];
            $data[$key]->Added = Date::createFromTimestamp($value->TimeStamp)->diffForHumans();
            $data[$key]->Ending = Date::createFromTimestamp($value->Expired)->diffForHumans();
        }

        return $data;
    }
}