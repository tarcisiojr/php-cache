<?php

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheStrategy;

class TTLCacheStrategy extends BaseCacheStrategy {

    const TTL_STATE_NAME = TTLCacheStrategy::class . '::' . 'ttl';

    private $ttl;

    public function __construct(CacheStrategy $strategy, $ttl = 0) {
        parent::__construct($strategy);

        $this->ttl = $ttl <= 0 ? 'forever' : $ttl;
    }

    public function init() {
        $state = parent::init();

        return array_merge($state, [
            self::TTL_STATE_NAME => $this->ttl == 0 ?
                'forever' : date("Y-m-d H:i:s", strtotime('now + ' . $this->ttl . ' seconds')),
        ]);
    }

    public function setValue($key, $value, $ttl = 0) {
        parent::setValue($key, $value, $this->ttl);
    }

    public function update($state) {
        list($newSate, $update) = parent::update($state);

        $ttl = isset($state[self::TTL_STATE_NAME]) ? $state[self::TTL_STATE_NAME] : 0;

        if ($ttl !== 'forever' && strtotime($ttl) < strtotime('now')) {
            $update = true;

            $newSate = array_merge($newSate, $this->init());
        }

        return [$newSate, $update];
    }
}
