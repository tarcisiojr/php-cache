<?php

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheStrategy;
use PHP\Cache\Core\System\FileCacheSystem;
use PHP\Cache\Core\System\StaticArrayCacheSystem;
use Symfony\Component\Yaml\Tests\ParseExceptionTest;

class ExactlyCacheStrategy extends BaseCacheStrategy {

    const COUNT_STATE_NAME = ExactlyCacheStrategy::class . '::' . 'count';

    private $times;

    public function __construct(CacheStrategy $strategy, $times = 0) {
        parent::__construct($strategy);

        $this->times = $times;
    }

    public function init() {
        return array_merge(parent::init(), [self::COUNT_STATE_NAME => 1]);
    }

    public function update($state) {
        list($newState, $update) = parent::update($state);

        if ($this->times > 0 && $newState[self::COUNT_STATE_NAME] >= $this->times) {
            return [array_merge($newState, $this->init()), true];
        }

        $newState[self::COUNT_STATE_NAME]++;

        return [$newState, $update];
    }
}
