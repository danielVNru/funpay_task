<?php

namespace FpDbTest;

use Exception;
use mysqli;

class Database implements DatabaseInterface
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function buildQuery(string $query, array $args = []): string
    {
        if (count($args) === 0) return $query;

        foreach ($args as $arg) {

            if (Utils::isEscape($arg)) {
                $query = Controllers::escape($query);

            } else if (is_string($arg)) {
                $query = Controllers::string($query, $arg);

            } else if (is_int($arg)) {
                $query = Controllers::integer($query, $arg);
                
            } else if (is_float($arg)) {
                $query = Controllers::float($query, $arg);

            } else if (is_bool($arg)) {
                $query = Controllers::boolean($query, $arg);

            } else if (is_null($arg)) {
                $query = Controllers::null($query, $arg);

            } else if (Utils::isAssoc($arg)) {
                $query =  Controllers::assoc($query, $arg);

            } else if (is_array($arg)) {
                $query = Controllers::array($query, $arg);
                
            } else {
                throw new Exception();
            }
        }

        $query = Controllers::removeConditionalBlocks($query);

        return $query;
    }

    public function skip()
    {
        return ESCAPE;
    }

}

