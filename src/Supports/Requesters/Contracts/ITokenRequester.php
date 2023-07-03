<?php

namespace Gouyuwang\IMessage\Supports\Requesters\Contracts;


interface ITokenRequester extends IRequester
{
    /**
     * @param bool|string $token
     */
    public function setToken($token);

    /**
     * @return string|null token
     */
    public function getToken();
}
