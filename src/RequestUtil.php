<?php


namespace Sysbot;


class RequestUtil
{
    public static function checkRequestOrigin(string $ip): bool
    {
        return self::ipInRange($ip, '149.154.160.0/20') or self::ipInRange($ip, '91.108.4.0/22');
    }

    public static function ipInRange($ip, $range): bool
    {
        if (strpos($range, '/') == false) {
            $range .= '/32';
        }
        [$range, $netmask] = explode('/', $range, 2);
        $range_decimal = ip2long($range);
        $ip_decimal = ip2long($ip);
        $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
        $netmask_decimal = ~$wildcard_decimal;
        return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
    }
}