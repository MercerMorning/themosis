<?php
namespace Theme\Controllers;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        global $post;
        $content = $post->post_content;
//        $content = str_replace("!!chat!!", $chat, " $post->post_content");
        return $content;
    }

    public function event()
    {
        NewMessageEvent::dispatch('ssssssssssss');
    }
}