<?php
// remote request_helper

namespace App\Helpers;

class Error
{
    public static $count = 0;
    public static $message = [];

    public static function add(string $code, string $message)
    {
        self::$count++;
        self::$message[] = ['code' => $code, 'message' => $message];
    }

    public static function getErrorMessage()
    {
        return self::$count == 0 ?  false : self::$message;
    }
}
