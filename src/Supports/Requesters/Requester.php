<?php

namespace Gouyuwang\IMessage\Supports\Requesters;


use Gouyuwang\IMessage\Supports\Requesters\Contracts\IRequester;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Requester implements IRequester
{
    /**
     * Request host
     *
     * @var string host
     */
    protected $base_uri;

    /**
     * Request options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Construct
     *
     * @param $base_uri
     */
    public function __construct($base_uri)
    {
        $this->base_uri = $base_uri;
    }

    /**
     * Do request
     *
     * @param $method
     * @param $path
     * @param array $data
     * @return mixed
     */
    public function request($method, $path, array $data = [])
    {
        $client = $this->getGuzzle();

        $type = 'query';

        if (strtoupper($method) != self::GET) {
            $type = 'json';
        }

        $response = $client->request($method, $path, [$type => $data]);

        return $this->parseResponse($response);
    }

    /**
     * parse response
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function parseResponse(ResponseInterface $response)
    {
        $body = $response->getBody();
        $result = json_decode($body, true);
        if ($result !== null) {
            return $result;
        }
        return $body;
    }


    /**
     * Get request client
     *
     * @return Client
     */
    public function getGuzzle(): Client
    {
        $options = array_merge($this->getOptions(), [
            'base_uri'    => $this->base_uri,
            'headers'     => [
                'Accept' => 'application/json'
            ],
            'http_errors' => false,
            'verify'      => false,
        ]);

        return new Client($options);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
