<?php

namespace App;

class Validator
{
    const MIN_COLUMNS_COUNT = 3;
    const TIMESTAMP_LENGHT = 10;

    /**
     * Строка валидна если в ней не меньше 3-х элементов и 3-й элемент это Unix Timestamp (временная метка)
     * @param string $string
     * @return bool
     */
    public static function validate(string $string): bool
    {
        $data = explode(';', $string);
        if (
            count($data) > self::MIN_COLUMNS_COUNT
            && strlen(strtotime($data[2])) === self::TIMESTAMP_LENGHT
        ) return true;

        return false;
    }
}
