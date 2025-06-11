<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('orders', function ($user) {
    return true; // Or check permissions, e.g., $user->isAdmin();
});