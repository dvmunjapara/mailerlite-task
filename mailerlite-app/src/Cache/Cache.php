<?php

namespace App\Cache;

use Predis\Client;

class Cache
{
    private Client $redis;

    public function __construct()
    {

        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => 6379,
        ]);
    }

    public function get(string $key): mixed
    {
        $data = $this->redis->get($key);
        if ($data !== null) {
            return unserialize($data);
        }
        return null;
    }

    public function set(string $key, mixed $data, int $ttl = 3600): void
    {
        $data = serialize($data);
        $this->redis->setex($key, $ttl, $data);
    }

    public function exists(string $key): int
    {
        return $this->redis->exists($key);
    }

    public function delete(string $key): int
    {
        return $this->redis->del($key);
    }

    public function flush(): void
    {
        $this->redis->flushall();
    }
}
