<?php
namespace Gouyuwang\IMessage\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method array|mixed broadcast($channel, $payload, bool $guest = false)
 * @method array|mixed subscribe($channel, $hooks, bool $guest = false)
 * @method array|mixed unsubscribe($channel)
 * @method array|mixed config($channel)
 */
class IMessage extends Facade
{
    /**
     * Return the facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'imessage.client';
    }
}
