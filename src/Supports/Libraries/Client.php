<?php

namespace Gouyuwang\IMessage\Supports\Libraries;


use Gouyuwang\IMessage\Providers\AbstractTokenProvider;
use Gouyuwang\IMessage\Supports\Requesters\Contracts\IRequester;
use Gouyuwang\IMessage\Supports\Requesters\TokenRequester;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Str;

class Client
{
    /**
     * @var IRequester
     */
    protected $requester;

    /**
     * @var string
     */
    protected $masterChannel;

    /**
     * @var array
     */
    protected $credentials = [];

    /**
     * @var string
     */
    protected $loginPath = '/login';

    /**
     * @var null|AbstractTokenProvider
     */
    protected $token = null;

    /**
     * @var string
     */
    protected $id;

    public function __construct(IRequester $requester, $masterChannel = 'master', $id = null)
    {
        $this->requester = $requester;
        $this->masterChannel = $masterChannel;
        $this->id = $id ?: Str::uuid();
    }

    /**
     * Broadcast
     *
     * @param $channel
     * @param $payload
     * @param bool $guest
     * @return array|mixed
     */
    public function broadcast($channel, $payload, bool $guest = false)
    {
        return $this->post('/broadcast', [
            'channels' => array_wrap($channel),
            'payload'  => $payload
        ], $guest);
    }

    /**
     * Subscribe
     *
     * @param $channel
     * @param $hooks
     * @param bool $guest
     * @return array|mixed
     */
    public function subscribe($channel, $hooks, bool $guest = false)
    {
        return $this->post('/subscribe', [
            'channels' => array_wrap($channel),
            'hooks'    => $hooks,
            'id'       => $this->getId($channel)
        ], $guest);
    }

    /**
     * Unsubscribe
     *
     * @param $channel
     * @return array|mixed
     */
    public function unsubscribe($channel)
    {
        return $this->post('/unsubscribe', [
            'channels' => array_wrap($channel),
            'id'       => $this->getId($channel)
        ]);
    }

    /**
     * Config
     *
     * @param array $configs
     * @return array|mixed
     */
    public function config(array $configs)
    {
        return $this->post('/config', $configs, false);
    }

    /**
     * Set token provider
     *
     * @param AbstractTokenProvider $provider
     * @return Client
     */
    public function setTokenProvider(AbstractTokenProvider $provider): Client
    {
        $this->token = $provider;

        return $this;
    }

    /**
     * Get token provider
     *
     * @return AbstractTokenProvider|null
     */
    public function getTokenProvider()
    {
        return $this->token;
    }

    /**
     * Supports post
     *
     * @param $path
     * @param array $data
     * @param bool $guest
     * @return array|mixed
     */
    public function post($path, array $data = [], bool $guest = false)
    {
        return $this->request(IRequester::POST, $path, $data, $guest);
    }

    /**
     * Supports get
     *
     * @param $path
     * @param array $data
     * @param bool $guest
     * @return array|mixed
     */
    public function get($path, array $data = [], bool $guest = false)
    {
        return $this->request(IRequester::GET, $path, $data, $guest);
    }

    /**
     * @param $method
     * @param $path
     * @param array $data
     * @param bool $guest
     * @return mixed|array
     */
    protected function request($method, $path, array $data = [], bool $guest = false)
    {
        $this->setToken($guest);

        return $this->requester->request($method, $path, $data);
    }

    /**
     * Get user
     *
     * @return array|mixed
     */
    public function user()
    {
        return $this->request(IRequester::GET, '/user');
    }

    /**
     * Get channels
     *
     * @return array|mixed
     */
    public function channels()
    {
        return $this->request(IRequester::GET, '/channels');
    }

    /**
     * Set credentials
     *
     * @param array $credentials
     * @return Client
     */
    public function setCredentials(array $credentials): Client
    {
        $this->credentials = $credentials;

        return $this;
    }

    /**
     * Get id
     *
     * @param $channel
     * @return string
     */
    protected function getId($channel): string
    {
        return $this->id . '-' . md5(json_encode(array_wrap($channel)));
    }

    /**
     * @param $guest
     */
    public function setToken($guest)
    {
        if (!$guest && ($tokenProvider = $this->getTokenProvider())) {
            if ($this->requester instanceof TokenRequester) {
                $tokenProvider->login($this->credentials, $this->loginPath);

                $this->requester->setToken($tokenProvider->getToken());
            }
        }
    }
}
