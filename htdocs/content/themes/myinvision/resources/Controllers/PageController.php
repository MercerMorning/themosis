<?php
namespace Theme\Controllers;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use Theme\Services\ThreadsListService;

class PageController extends Controller
{
    public function index()
    {
        global $post;
        $threads =
//        $threadsAndUsers = ThreadsListService::getWholeList();
//        dd($threadsAndUsers);
        $chat = view('front.chat', [
//            'currentUser' => json_encode($currentUser->toArray()),
//            'threads' => json_encode($threadsAndUsers['threads']),
//            'users' => json_encode($threadsAndUsers['users']),
        ])->render();
        return $chat;
//        global $post;
//        $content = $post->post_content;
////        $content = str_replace("!!chat!!", $chat, " $post->post_content");
//        return $content;
    }

    public function event()
    {
        return view('layouts.main');
//        NewMessageEvent::dispatch('ssssssssssss');
    }
}