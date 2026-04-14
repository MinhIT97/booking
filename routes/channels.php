<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // Only the receiving user or sending user can listen to their private chat channel
    return $user->id === $userId;
});
