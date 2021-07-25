<?php

namespace Theme\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadParticipant extends Model
{
    protected $table = 'participants_table_message';

    protected $fillable = ['thread_id', 'user_id'];
}