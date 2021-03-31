<?php
namespace Oino\Helpers;

class CliHelper
{
    const ERROR = 1;

    const SUCCESS = 2;

    const WARNING = 3;

    const INFO = 4;

    public static function write(string $str, $type = null): bool
    {
        switch ($type) {
            case self::ERROR:
                return print("\033[31m$str \033[0m" . PHP_EOL);
            case self::SUCCESS:
                return print("\033[32m$str \033[0m" . PHP_EOL);
            case self::WARNING:
                return print("\033[33m$str \033[0m" . PHP_EOL);
            case self::INFO:
                return print("\033[36m$str \033[0m" . PHP_EOL);
            default:
                return print($str . PHP_EOL);
        }
    }
}