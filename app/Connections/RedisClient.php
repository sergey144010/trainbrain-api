<?php

namespace App\Connections;

class RedisClient
{
    private \Redis $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('redis', 6379);
    }

    public function redis(): \Redis
    {
        return $this->redis;
    }
}
