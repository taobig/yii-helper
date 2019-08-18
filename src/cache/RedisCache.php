<?php

namespace taobig\yii\cache;

use yii\redis\Cache;

class RedisCache extends Cache
{

    public function buildKey($key): string
    {
        if (is_string($key)) {
            $key = str_replace(' ', '-', $key);
            return $this->keyPrefix . $key;
        } else {
            return parent::buildKey($key);
        }
    }

}