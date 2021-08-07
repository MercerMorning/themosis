<?php

namespace Theme\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\Model;
use Theme\Helpers\AuthUser;

class User extends Model
{
    use Messagable;

    protected $table = 'users';

    public function token()
    {
        return UserToken::query()
            ->where('user_id', (int) $this->ID)
            ->first();
    }
}