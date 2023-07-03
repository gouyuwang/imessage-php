<?php
namespace Gouyuwang\IMessage\Providers;

use Gouyuwang\IMessage\Supports\Libraries\Token;
use Carbon\Carbon;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;

class TokenProvider extends AbstractTokenProvider
{
    /**
     * Cache key
     *
     * @var string
     */
    protected $cacheKey = 'imessage:token:';

    /**
     * Cache driver
     *
     * @var null
     */
    protected $cacheDriver = null;

    /**
     * Load token from cache
     *
     * @return Token|null
     */
    public function loadTokenFromCache()
    {
        $cache = $this->getCacheDriver();

        $data = $cache->get($this->cacheKey);

        if (!empty($data) && isset($data['token'])) {
            $token = new Token($data['token'], $data['exp'] ?? null);
            $this->token = $token;
            return $token;
        }

        return null;
    }

    /**
     * Store token to cache
     *
     * @param Token $token
     * @return void
     */
    public function storeTokenToCache(Token $token)
    {
        $t = $token->getToken();
        $exp = $token->getExpireAt();
        $cache = $this->getCacheDriver();

        $data = [
            'token' => $t,
            'exp'   => $exp
        ];

        if (is_null($exp)) {
            $cache->forever($this->cacheKey, $data);
        } else {
            $cache->put($this->cacheKey, $data, Carbon::createFromTimestamp($exp));
        }
    }

    /**
     * Get the cache driver.
     *
     * @return mixed
     */
    protected function getCacheDriver()
    {
        return app('cache')->driver($this->cacheDriver);
    }
}
