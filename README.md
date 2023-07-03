# IMessage master client

## Require

[IMessage-Server](https://github.com/gouyuwang/imessage-server)

## Install

```Shell
composer require gouyuwang/imessage-php
```

## Configuration

```shell
php artisan vendor:publish --provider="Gouyuwang\IMessage\ServiceProvider" --tag=imessage
```

```php
return [
    'host'        => env('IMESSAGE_NODE_HOST', 'http://127.0.0.1:3003'), // IMessage-Server host
    'master'      => env('IMESSAGE_MASTER_CHANNEL', 'master'), // channel
    'credentials' => [ // auth
        'key' => env('IMESSAGE_MASTER_KEY', 'masterkey'),
        '_id' => env('IMESSAGE_CLIENT_ID', 'somerandomid'),
    ],
];
```


## Use

`Broadcast`： IMessage::broadcast('channel','payload')
