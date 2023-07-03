<?php

namespace Gouyuwang\IMessage\Controllers;

use Gouyuwang\IMessage\Events\IMessageStartEvent;
use Illuminate\Http\Request;

class IndexController
{
    /**
     * Start node
     *
     * @param Request $request
     * @return string
     */
    public function start(Request $request): string
    {
        event(new IMessageStartEvent($request));

        return 'ok';
    }
}
