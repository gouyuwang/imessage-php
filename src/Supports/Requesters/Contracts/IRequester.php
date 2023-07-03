<?php

namespace Gouyuwang\IMessage\Supports\Requesters\Contracts;


interface IRequester
{
    /**
     * @const HTTP Post
     */
    const POST = 'POST';

    /**
     * @const HTTP Get
     */
    const GET = 'GET';

    /**
     * Request service
     *
     * @param $method
     * @param $path
     * @param array $data
     * @return mixed
     */
    public function request($method, $path, array $data = []);
}
