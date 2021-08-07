<?php

namespace Theme\Models;

use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Models;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\Model;
use Theme\Helpers\AuthUser;

class Thread extends \Cmgmyr\Messenger\Models\Thread
{
    public function messages()
    {
        return $this->hasMany(Models::classname(\Theme\Models\Message::class), 'thread_id', 'id');
    }
}