<?php

namespace App;
class Logger
{
    /**
     * @param mixed $data
     * @param string $file
     * @return void
     */
    public static function log(mixed $data, string $file): void
    {
        $log = PATH . RUNTIME_DIR . $file;
        error_log(date('Y/m/d H:i:s') . ": $data" . PHP_EOL, 3, $log);
    }
}
