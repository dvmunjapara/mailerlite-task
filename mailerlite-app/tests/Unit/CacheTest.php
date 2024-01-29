<?php

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
use App\Cache\Cache;

it('can get and set cache', function () {
    $cache = new Cache();

    $cache->set('test', 'test');

    $this->assertSame('test', $cache->get('test'));
});

it('can delete cache', function () {
    $cache = new Cache();

    $cache->set('test', 'test');

    $this->assertSame('test', $cache->get('test'));

    $cache->delete('test');

    $this->assertNull($cache->get('test'));
});

it('can flush cache', function () {
    $cache = new Cache();

    $cache->set('test', 'test');

    $this->assertSame('test', $cache->get('test'));

    $cache->flush();

    $this->assertNull($cache->get('test'));
});
