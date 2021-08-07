<?php

namespace Theme\Helpers;

use Theme\Models\User;

class AuthUser
{
    public static function currentUser()
    {
        return User::find(wp_get_current_user()->ID);
    }

    public static function currentUserId()
    {
        return User::find(wp_get_current_user()->ID)->ID;
    }
}