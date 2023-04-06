<?php

namespace App\Websockets\Exceptions;

use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\WebSocketException;

class InvalidCredentials extends WebSocketException
{
    public function __construct(string $deviceId, string $token)
    {
        $this->message = "Could not find credential for `{$deviceId}` with token `{$token}`.";
        $this->code = 4001;
    }
}
