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
        $content = $post->post_content;
        if (strpos($content, "!!chat!!")) {
            $chat = new MessagesController();
            $chat = $chat->showChat();
            $content = str_replace("!!chat!!", $chat, " $post->post_content");
        };

        return $content;
    }

    public function event()
    {
        return view('layouts.main');
//        NewMessageEvent::dispatch('ssssssssssss');
    }
}