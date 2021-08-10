<?php

namespace Theme\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $table = 'threads';

    protected $fillable = ['subject', 'private', 'is_private'];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i',
    ];

    public function messages()
    {
        return $this->hasMany(ThreadMessage::class, 'thread_id', 'id')->get();
    }

    public function lastMessage()
    {
        return ThreadMessage::query()
            ->where('thread_id', $this->id)
            ->orderBy('created_at', 'DESC')
            ->first() ?? '';
    }

    public function participants()
    {
        return $this->hasMany(ThreadParticipant::class);
    }
}