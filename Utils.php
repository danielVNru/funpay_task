<?php

namespace FpDbTest;

use mysqli;

class Utils
{
    public static function isAssoc($array)
    {
        if (!is_array($array)) return false;
        return (array_keys($array) !== range(0, count($array) - 1));
    }

    public static function removeSpace($str)
    {
        return str_replace(' ', '', $str);
    }

    public static function getFirstSpecChar($str)
    {
        $specCharWithSpace = substr($str, strpos($str, '?'), 2);
        $specChar = self::removeSpace($specCharWithSpace);
        return $specChar;
    }

    public static function shieldingArray($array)
    {
        $newArray = array_map(fn ($item) => (is_string($item) ? "'$item'" : $item), $array);

        return $newArray;
    }

    public static function returnNULL()
    {
        return 'NULL';
    }

    public static function replaceFirstPos($search, $replace, $subject)
    {
        $pos = strpos($subject, $search);
        return substr($subject, 0, $pos) . $replace . substr($subject, $pos + strlen($search), strlen($subject));
    }

    public static function isEscape($val)
    {
        return ($val === ESCAPE);
    }
}
