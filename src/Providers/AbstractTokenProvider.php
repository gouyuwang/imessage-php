<?php

namespace Gouyuwang\IMessage\Providers;

use Gouyuwang\IMessage\Supports\Libraries\Token;
use Gouyuwang\IMessage\Supports\Requesters\Contracts\IRequester;
use Gouyuwang\IMessage\Supports\Requesters\TokenRequester;

abstract class AbstractTokenProvider
{
    /**
     * @var IRequester
     */
    protected $requester;

    /**
     * @var string token
     */
    protected $token;

    /**
     * Token file
     *
     * @var string
     */
    protected $tokenFile = __DIR__ . '/../__token';

    /**
     * @param IRequester $requester
     */
    public function __construct(IRequester $requester)
    {
        $this->requester = $requester;
    }

    /**
     * Login
     *
     * @param $credentials
     * @param string $path
     * @return Token|string
     */
    public function login($credentials, string $path = '/login')
    {
        $this->loadTokenFromCache();

        if ($this->token) {
            return $this->token;
        }

        if ($this->requester instanceof TokenRequester) {
            $this->requester->setToken(null);
        }

        $token = $this->requester->request(IRequester::POST, $path, $credentials);

        $t = $this->parseToken($token);

        $this->storeTokenToCache($t);

        return $t;
    }

    /**
     * Parse token
     *
     * @param $token
     * @return Token|string
     */
    public function parseToken($token)
    {
        $exp = null;
        if (isset($token['exp'])) {
            $exp = intval(substr($token['exp'], 0, 10));
        }
        $this->token = new Token($token['token'], $exp);
        return $this->token;
    }

    /**
     * Getter Token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Load token from cache
     *
     * @return mixed|null
     */
    abstract function loadTokenFromCache();


    /**
     * Store token to cache
     *
     * @param Token $token
     * @return void
     */
    abstract function storeTokenToCache(Token $token);

}
