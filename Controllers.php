<?php

namespace FpDbTest;

use Exception;

class Controllers
{

    public static function array($query, $array)
    {
        if (count($array) === 0) throw new Exception();

        $specChar = Utils::getFirstSpecChar($query);

        if ($specChar === '?#') {
            $array = array_map(fn ($item) => ("`$item`"), $array);

        } else if ($specChar === '?a') {
            $array = Utils::shieldingArray($array);

        } else {
            throw new Exception();
        }

        $query = Utils::replaceFirstPos($specChar, join(', ', $array), $query);


        return $query;
    }

    public static function string($query, $value)
    {

        $specChar = Utils::getFirstSpecChar($query);

        if ($specChar === '?') {
            $value = "'$value'";

        } else if ($specChar === '?#') {
            $value = "`$value`";

        } else {
            throw new Exception();
        }

        $query = Utils::replaceFirstPos($specChar, $value, $query);

        return $query;
    }

    public static function assoc($query, $array)
    {

        $specChar = Utils::getFirstSpecChar($query);

        if ($specChar === '?a') {

            $substrArray = [];

            foreach ($array as $key => $value) {
                if (is_null($value)) {
                    $value = Utils::returnNULL();

                } else if (is_string($value)){
                    $value = "'$value'";

                } else if (! is_numeric($value)) {
                    throw new Exception();
                }
                $substrArray[] = "`$key` = ". $value;
            }

            $query = Utils::replaceFirstPos($specChar, join(', ', $substrArray), $query);

        } else {
            throw new Exception();
        }

        return $query;
    }

    public static function integer($query, $value)
    {
        $specChar = Utils::getFirstSpecChar($query);

        if (
            $specChar === '?d' ||
            $specChar === '?f' ||
            $specChar === '?'
        ) {
            $query = Utils::replaceFirstPos($specChar, $value, $query);

        } else {
            throw new Exception();
        }

        return $query;
    }

    public static function float($query, $value)
    {
        $specChar = Utils::getFirstSpecChar($query);

        if (
            $specChar === '?f' ||
            $specChar === '?'
        ) {
            $query = Utils::replaceFirstPos($specChar, (float)$value, $query);

        } else {
            throw new Exception();
        }

        return $query;
    }

    public static function boolean($query, $value)
    {

        $specChar = Utils::getFirstSpecChar($query);

        if (
            $specChar === '?d' ||
            $specChar === '?f' ||
            $specChar === '?'
        ) {
            $query = Utils::replaceFirstPos($specChar, (int)$value, $query);

        } else {
            throw new Exception();
        }
        return $query;
    }

    public static function null($query) 
    {
        $specChar = Utils::getFirstSpecChar($query);

        if (
            $specChar === '?d' ||
            $specChar === '?f' ||
            $specChar === '?'
        ) {
            $query = Utils::replaceFirstPos($specChar, 'NULL', $query);

        } else {
            throw new Exception();
        }
        return $query;
    }

    public static function escape($query) 
    {
        $specChar = Utils::getFirstSpecChar($query);
        $query = Utils::replaceFirstPos($specChar, ESCAPE, $query);

        return $query;
    }

    public static function removeConditionalBlocks($query) 
    {
        $pattern = '/{[^{}]*'. ESCAPE. '[^{}]*}/';

        $query = preg_replace($pattern, '', $query);
        $query = str_replace(['{', '}'], '', $query);

        return $query;
    }
}
