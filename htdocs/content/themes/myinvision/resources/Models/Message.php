<?php

namespace Theme\Models;


class Message extends \Cmgmyr\Messenger\Models\Message
{
    public function attachment()
    {
        return $this
            ->hasOne(Attachment::class, 'message_id', 'id');
    }
}