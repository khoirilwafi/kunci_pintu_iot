<?php

use App\Websockets\Handlers\DoorSocketHandler;
use App\Websockets\Handlers\OfficeSocketHandler;
use Illuminate\Support\Facades\Broadcast;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('public.channel', function () {
    return true;
});

Broadcast::channel('private.dashboard.{id}', function ($user, $id) {
    return $user->id === $id && $user->role === 'operator';
});

WebSocketsRouter::webSocket('/door-connect', DoorSocketHandler::class);
WebSocketsRouter::webSocket('/office-connect', OfficeSocketHandler::class);
