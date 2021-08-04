<?php

namespace Theme\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $table = 'threads';

    protected $fillable = ['subject', 'private'];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    public function messages()
    {
        return $this->hasMany(ThreadMessage::class, 'thread_id', 'id')->get();
    }
}