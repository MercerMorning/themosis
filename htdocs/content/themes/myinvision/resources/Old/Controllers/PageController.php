<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Theme\Services\ThreadsListService;

class PageController extends Controller
{
    public function index()
    {
//        $request = new Request(['thread_id' => 171]);
//        $threadC = new ThreadController();
//        dd($threadC->getThreadMessages($request));

        global $post;
        $threadsAndUsers = ThreadsListService::getWholeList();
//        dd($threadsAndUsers);
        $chat = view('front.chat', [
//            'currentUser' => json_encode($currentUser->toArray()),
//            'threads' => json_encode($threadsAndUsers['threads']),
//            'users' => json_encode($threadsAndUsers['users']),
        ])->render();
        return $chat;
//        $content = str_replace("!!chat!!", $chat, " $post->post_content");
//        return $content;
    }
}