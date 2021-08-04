<?php

namespace Theme\Helpers;

use Theme\Models\User;

class AuthUser
{
    public static function currentUser()
    {
        User::find(wp_get_current_user()->ID);
    }

    public static function currentUserId()
    {
        User::find(wp_get_current_user()->ID)->ID;
    }
}