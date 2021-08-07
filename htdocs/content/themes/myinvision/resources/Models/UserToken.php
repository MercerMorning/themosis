<?php

namespace Theme\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use Messagable;

    protected $table = 'user_tokens';

    protected $fillable = ['user_id', 'token'];

    public $timestamps = false;
}