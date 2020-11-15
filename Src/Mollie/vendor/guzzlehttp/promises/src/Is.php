<?php

namespace _PhpScoper5f8847d7a6e47\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     *
     * @return bool
     */
    public static function pending(\_PhpScoper5f8847d7a6e47\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScoper5f8847d7a6e47\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     *
     * @return bool
     */
    public static function settled(\_PhpScoper5f8847d7a6e47\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() !== \_PhpScoper5f8847d7a6e47\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     *
     * @return bool
     */
    public static function fulfilled(\_PhpScoper5f8847d7a6e47\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScoper5f8847d7a6e47\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     *
     * @return bool
     */
    public static function rejected(\_PhpScoper5f8847d7a6e47\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \_PhpScoper5f8847d7a6e47\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
