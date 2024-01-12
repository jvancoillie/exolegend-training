<?php

namespace App\Input;

class Referee
{
    public static $input = [];

    public static function addEntry(string $entry): void
    {
        self::$input[] = $entry;
    }

    public static function reset(): void
    {
        self::$input = [];
    }

    public static function getJsonInput(): string
    {
        return json_encode(self::$input);
    }

    public static function dump(): void
    {
        error_log(var_export(json_encode(self::$input), true));
    }
}
