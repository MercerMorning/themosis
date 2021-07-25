<?php

namespace Theme\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $table = 'threads';

    protected $fillable = ['subject'];

    public function messages()
    {
        return $this->hasMany(ThreadMessage::class, 'thread_id', 'id')->get();
    }
}