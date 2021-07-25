<?php

Route::get('/',
    [\Theme\Controllers\ChatController::class,
        'index'])->name('chat');

Route::post('chat/create_thread/',
    [\Theme\Controllers\ThreadController::class,
        'add'])->name('add_thread');

Route::post('chat/send_message_to_user/',
    [\Theme\Controllers\PersonalCorrespondence::class,
        'sendMessage'])->name('send_message_to_user');

Route::post('chat/invite_to_thread/',
    [\Theme\Controllers\ThreadController::class,
        'inviteParticipant'])->name('invite_participant');