<?php
/**
 * Created by PhpStorm.
 * User: kubas
 * Date: 26.06.2018
 * Time: 21:23
 */

namespace App\Libraries;


class SocketLibrary
{
    const TypeReloadGroup = 0;

    public static function Send($funcName, $data)
    {
        $output = [
            "Func" => $funcName,
            "Data" => $data
        ];

        $sock = \socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        $msg = json_encode($output);
        $len = strlen($msg);

        socket_sendto($sock, $msg, $len, 0, '127.0.0.1', 22020);
        socket_close($sock);

       return true;
    }
}