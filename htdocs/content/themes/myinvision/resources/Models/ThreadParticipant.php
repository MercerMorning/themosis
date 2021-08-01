<?php

namespace Theme\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadParticipant extends Model
{
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    protected $table = 'participants_table_message';

    protected $fillable = ['thread_id', 'user_id'];
}