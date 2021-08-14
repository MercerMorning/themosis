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
Route::get('chat/get_threads/{userId}',
    [\Theme\Controllers\MessagesController::class,
        'getAllThreads'])->name('get_threads');

Route::get('chat/get_thread/',
    [\Theme\Controllers\MessagesController::class,
        'showThread'])
    ->middleware('participanting')
    ->name('get_thread');

Route::post('chat/send_message_to_thread/',
    [\Theme\Controllers\MessagesController::class,
        'storeMessage'])
    ->middleware('participanting')
    ->name('send_message_to_thread');

Route::post('chat/create_group_thread/',
    [\Theme\Controllers\MessagesController::class,
        'store'])->name('create_group_thread');

Route::post('chat/create_private_thread/',
    [\Theme\Controllers\MessagesController::class,
        'storePrivate'])->name('create_private_thread');

Route::post('chat/block_thread/',
    [\Theme\Controllers\MessagesController::class,
        'blockThread'])->name('block_thread');

Route::any('page',
    [\Theme\Controllers\PageController::class,
        'index']);
