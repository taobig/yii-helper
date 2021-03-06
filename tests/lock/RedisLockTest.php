<?php

namespace taobig\tests\yii\lock;

use taobig\helpers\lock\exceptions\LockFailedException;
use taobig\helpers\lock\RedisLock;
use taobig\helpers\lock\YiiRedisConnection;
use yii\redis\Connection;

class RedisLockTest extends \TestCase
{

    public function testLockWithYiiRedisConnection()
    {
        $redis = new Connection();
        $redisConnection = new YiiRedisConnection($redis);
        foreach ([true, false] as $enableEvalCommand) {
            $redisLock = new RedisLock($redisConnection, '1', $enableEvalCommand);

            $this->assertSame(RedisLock::class, $redisLock->name());
            $this->assertSame(RedisLock::class, $redisLock->getName());

            $lockedKey = "testtesttest_" . time();
            $lockedValue = $redisLock->lock($lockedKey, 200);
            $this->assertSame(true, is_int($lockedValue));

            $this->expectException(LockFailedException::class);
            $redisLock->lock($lockedKey, 200);
        }
    }


    public function testUnlockWithYiiRedisConnection()
    {
        $redis = new Connection();
        $redisConnection = new YiiRedisConnection($redis);
        foreach ([true, false] as $enableEvalCommand) {
            $redisLock = new RedisLock($redisConnection, '1', $enableEvalCommand);

            $lockedKey = "testtesttest_" . time();;
            $lockedValue = $redisLock->lock($lockedKey, 200);
            $this->assertSame(true, is_int($lockedValue));

            $redisLock->unlock($lockedKey, $lockedValue);

            $redisLock->lock($lockedKey, 200);
        }
    }

}