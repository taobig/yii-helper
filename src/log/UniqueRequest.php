<?php


namespace taobig\yii\log;


class UniqueRequest
{
    private static $_unique_request_id = null;

    public static function getId(): string
    {
        if (is_null(self::$_unique_request_id)) {
            self::$_unique_request_id = self::_generateId();
        }
        return self::$_unique_request_id;
    }

    private static function _generateId(): string
    {
        $host = gethostname();
        $pid = sprintf('%05s', getmypid());
        return uniqid("$host-$pid-");
    }
}