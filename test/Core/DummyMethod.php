<?php

namespace Test\PHP\Cache\Core;

use PHP\Cache\Core\Cache;

trait DummyMethod
{
    private function createDummyBase($a = "a", $b = "b")
    {
        return Cache::create(function () use ($a, $b) {
            return rand(1, 10000);
        });
    }

    public function dummyMethod($a = "a", $b = "b")
    {
        return $this->createDummyBase($a, $b)
            ->once()
            ->statefull()
            ->ttl(10)
            ->get()
            ;
    }
}
