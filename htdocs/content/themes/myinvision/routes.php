<?php

Route::get('/',
    [\Theme\Controllers\ChatController::class,
        'index']);

Route::post('chat/create_thread',
    [\Theme\Controllers\ThreadController::class,
        'add']);

Route::post('chat/invite_to_thread/{{ thread_id }}',
    [\Theme\Controllers\ThreadController::class,
        'invite_participant']);