<?php

//Route::get('/',
//    [\Theme\Controllers\PageController::class,
//        'event'])->name('chat');
//
//Route::get('/checking',
//    [\Theme\Controllers\PageController::class,
//        'event']);
//
//Route::post('chat/create_thread/',
//    [\Theme\Controllers\ThreadController::class,
//        'add'])->name('add_thread');
//
Route::get('chat/get_threads/',
    [\Theme\Controllers\MessagesController::class,
        'getAll'])->name('get_threads');

Route::get('chat/get_thread/',
    [\Theme\Controllers\MessagesController::class,
        'showThread'])->name('get_thread');

Route::post('chat/send_message_to_thread/',
    [\Theme\Controllers\MessagesController::class,
        'storeMessage'])->name('send_message_to_thread');

Route::post('chat/create_group_thread/',
    [\Theme\Controllers\MessagesController::class,
        'store'])->name('create_group_thread');
//
//Route::post('chat/send_message_to_user/',
//    [\Theme\Controllers\PersonalCorrespondence::class,
//        'sendMessage'])->name('send_message_to_user');
//
//
//Route::post('chat/invite_to_thread/',
//    [\Theme\Controllers\ThreadController::class,
//        'inviteParticipant'])->name('invite_participant');
//
Route::any('page',
    [\Theme\Controllers\MessagesController::class,
        'showChat']);
