<?php

namespace Theme\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadMessage extends Model
{
    protected $table = 'messages';

    protected $fillable = ['body', 'thread_id', 'user_id'];
}