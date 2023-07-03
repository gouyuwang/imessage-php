<?php
namespace Gouyuwang\IMessage\Facades;

use Illuminate\Support\Facades\Facade;

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
