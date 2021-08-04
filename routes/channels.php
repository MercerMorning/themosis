<?php
use Illuminate\Support\Facades\Broadcast;

//Broadcast::channel('chat', function () {
//    return true;
//});

//Broadcast::channel('chat.{thread_id}', function ($user, $threadId) {
//    $thread = \Theme\Models\Thread::find($threadId);
//
//    if (!$thread) {
//        return false;
//    }
//
//    $participantsUserIds = collect($thread->participantsUserIds());
//
//    if ($participantsUserIds->contains($user->id)) {
//        return true;
//    }
//
//    return false;
//});