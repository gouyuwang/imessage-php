<?php

namespace Gouyuwang\IMessage\Supports\Requesters;


use Gouyuwang\IMessage\Supports\Requesters\Contracts\ITokenRequester;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class TokenRequester extends Requester implements ITokenRequester
{
    /**
     * @var string|null token
     */
    protected $token = null;

    /**
     * @param bool|string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string|null token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Request with token
     *
     * @return array
     */
    public function getOptions(): array
    {
        $options = parent::getOptions();
        ($token = $this->getToken()) && $options['headers']['Authorization'] = 'Bearer ' . $token;
        return $options;
    }
}
