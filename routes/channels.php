<?php

use App\Models\Office;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('office.{id}', function ($user, $id) {
    return Office::where('id', $id)->where('user_id', $user->id)->first() != null ? true : false;
});
