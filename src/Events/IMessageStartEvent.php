<?php
namespace Gouyuwang\IMessage\Events;

use Illuminate\Http\Request;

class IMessageStartEvent
{
    /**
     * @var Request
     */
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
