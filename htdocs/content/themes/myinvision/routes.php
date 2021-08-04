<?php

//Route::get('/',
//    [\Theme\Controllers\ChatController::class,
//        'index'])->name('chat');
//
//Route::post('chat/create_thread/',
//    [\Theme\Controllers\ThreadController::class,
//        'add'])->name('add_thread');
//
//Route::get('chat/get_threads/',
//    [\Theme\Controllers\ThreadController::class,
//        'getAll'])->name('get_threads');
//
//Route::get('chat/get_thread/',
//    [\Theme\Controllers\ThreadController::class,
//        'getThreadMessages'])->name('get_thread');
//
//Route::post('chat/send_message_to_user/',
//    [\Theme\Controllers\PersonalCorrespondence::class,
//        'sendMessage'])->name('send_message_to_user');
//
//Route::post('chat/send_message_to_thread/',
//    [\Theme\Controllers\MessageController::class,
//        'send'])->name('send_message_to_thread');
//
//Route::post('chat/invite_to_thread/',
//    [\Theme\Controllers\ThreadController::class,
//        'inviteParticipant'])->name('invite_participant');
//
Route::any('page',
    [\Theme\Controllers\PageController::class,
        'index']);
