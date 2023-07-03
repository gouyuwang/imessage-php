<?php
declare(strict_types=1);

namespace Gouyuwang\IMessage;

return [
    'host'        => env('IMESSAGE_NODE_HOST', 'http://127.0.0.1:3003'),
    'master'        => env('IMESSAGE_MASTER_CHANNEL', 'master'),
    'credentials' => [
        'key' => env('IMESSAGE_MASTER_KEY', 'masterkey'),
        '_id' => env('IMESSAGE_CLIENT_ID', 'somerandomid'),
    ],
];
