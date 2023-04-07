<?php

use Illuminate\Support\Facades\Broadcast;

use App\Websockets\DoorSocketHandler;
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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('public.testing', function () {
    return true;
});

Broadcast::channel('private.dashboard.{id}', function ($user, $id) {
    return $user->id === $id && $user->role === 'operator';
});

WebSocketsRouter::webSocket('/door-connect', DoorSocketHandler::class);
