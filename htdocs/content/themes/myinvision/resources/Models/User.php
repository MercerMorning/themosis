<?php

namespace Theme\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Messagable;

    protected $table = 'users';
}