<?php
/**
 * Created by PhpStorm.
 * User: kubas
 * Date: 04.07.2018
 * Time: 22:20
 */

namespace App\Libraries;


class ItemLibrary
{
    public static function GetItemTypes()
    {
        return [
            0 => "Nieznany",
            1 => "Broń",
            2 => "Amunicja",
            3 => "Telefon",
            4 => "Megafon",
            5 => "Napój",
            6 => "Jedzenie",
            7 => "Maska",
            8 => "Skin",
            9 => "Lakier",
            10 => "Narkotyki",
            11 => "Kostka do gry",
            12 => "Zwłoki",
            13 => "Lek",
            14 => "Część tuningowa",
            15 => "Kanister",
            16 => "Papierosy",
            17 => "Topup",
            18 => "Tempomat",
            19 => "Alkohol",
            20 => "Karty do gry"
        ];
    }

    public static function GetItemTypeName($itemType)
    {
        $types = self::GetItemTypes();
        if (array_key_exists($itemType, $types))
        {
            return $types[$itemType];
        }
        return "Nieznany";
    }
}