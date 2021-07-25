<?php

//Route::get('/', function () {
//    dd(\Theme\Models\User::find(1)->threads());
//});

Route::get('/',
    [\Theme\Controllers\ChatController::class,
        'index'])->name('chat');

Route::post('chat/create_thread/',
    [\Theme\Controllers\ThreadController::class,
        'add'])->name('add_thread');

Route::get('chat/get_threads/',
    [\Theme\Controllers\ThreadController::class,
        'getAll'])->name('get_threads');

Route::post('chat/send_message_to_user/',
    [\Theme\Controllers\PersonalCorrespondence::class,
        'sendMessage'])->name('send_message_to_user');

Route::post('chat/invite_to_thread/',
    [\Theme\Controllers\ThreadController::class,
        'inviteParticipant'])->name('invite_participant');