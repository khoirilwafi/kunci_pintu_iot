<?php

use App\Websockets\Handlers\DoorSocketHandler;
use App\Websockets\Handlers\OfficeSocketHandler;
use Illuminate\Support\Facades\Broadcast;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use React\Http\Browser;

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

Broadcast::channel('public.channel', function ($user) {
    return true;
});

Broadcast::channel('dashboard.{id}', function ($user, $id) {
    return $user->id === $id && $user->role === 'operator';
});

Broadcast::channel('door.{id}.{token}', function () {
});
