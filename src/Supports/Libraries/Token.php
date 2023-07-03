<?php

namespace Gouyuwang\IMessage\Supports\Libraries;


class Token
{
    /**
     * @var string
     */
    protected $token;
    /**
     * @var int|null
     */
    protected $expire_at;

    public function __construct(string $token, $expire_at = null)
    {
        $this->token = $token;
        $this->expire_at = $expire_at;
    }

    public function __toString()
    {
        return $this->token;
    }

    /**
     * @return int|null
     */
    public function getExpireAt()
    {
        return $this->expire_at;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
