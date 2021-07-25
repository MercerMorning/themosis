<?php

namespace Theme\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    public function threads() {
        return ThreadParticipant::query()
            ->join('threads', 'participants_table_message.thread_id', '=', 'threads.id')
            ->where('user_id', wp_get_current_user()->ID)
            ->get(['threads.id', 'threads.subject']);
    }
}