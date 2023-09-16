<?php

namespace Lamine\License\Tools;

class Hardware
{
    static function cpu()
    {
        // Get CPU ID
        $cpuId = trim(shell_exec("wmic cpu get ProcessorId"));
        $cpuId = str_replace("ProcessorId", "", $cpuId);
        $cpuId = trim($cpuId);
        $cpuId = str_replace("\n", "", $cpuId);
        return str_replace("\r", "", $cpuId) ?? 'not-defined';
    }

    static function motherboard()
    {
        // Get Motherboard ID
        $motherboardId = trim(shell_exec("wmic baseboard get SerialNumber"));
        $motherboardId = str_replace("SerialNumber", "", $motherboardId);
        $motherboardId = trim($motherboardId);
        $motherboardId = str_replace("\n", "", $motherboardId);
        return str_replace("\r", "", $motherboardId) ?? 'not-defined';
    }

    static function mac()
    {
        $ipconfig = shell_exec('ipconfig /all');
        $matches = [];
        preg_match('/Physical Address[\. ]+: ([0-9A-Fa-f\-:]+)/', $ipconfig, $matches);
        return isset($matches[1]) ? str_replace('-', ':', $matches[1]) : null;
    }
}
