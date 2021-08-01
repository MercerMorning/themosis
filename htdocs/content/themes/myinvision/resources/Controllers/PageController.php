<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        global $post;
        $chat = new ChatController();
        $chat = $chat->index();
        $chat = $chat->render();
        $content = str_replace("!!chat!!", $chat, " $post->post_content");

        return $content;
    }
}