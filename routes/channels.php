<?php

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

use Illuminate\Support\Facades\Auth;

Broadcast::channel('room.{room_id}', function ($user, $room_id) {
    if ((int) $user->rooms->contains($room_id) && $user->rooms->every(function($value, $key) {
            return $value->pivot->is_baned == 0;
        })) {
        return [
            'name' => $user->name,
            'id' => $user->id,
        ];
    }
});
