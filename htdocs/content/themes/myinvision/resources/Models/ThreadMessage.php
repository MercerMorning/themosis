<?php

namespace Theme\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadMessage extends Model
{
    protected $table = 'messages';

    protected $fillable = ['body', 'thread_id', 'user_id'];

    protected $casts = [
        'created_at' => 'datetime: H:i',
        'updated_at' => 'datetime: H:i',
    ];

    public function messagePresenter()
    {
        $messageUser = User::find($this->user_id);
        if ($messageUser->ID == wp_get_current_user()->ID) {
            $userName = 'Вы: ';
        } else {
            $userName = User::find($this->user_id)->user_login;
        }
        return $userName . ': ' . $this->body;
    }
}