<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Theme\Models\Thread;
use Theme\Models\ThreadMessage;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;
use Theme\Services\ThreadsListService;
use function foo\func;

class ChatController extends Controller
{
    public function index()
    {
        $threadsAndUsers = ThreadsListService::getWholeList();
//        dd($threadsAndUsers);
        return view('front.chat', [
//            'currentUser' => json_encode($currentUser->toArray()),
            'threads' => json_encode($threadsAndUsers['threads']),
            'users' => json_encode($threadsAndUsers['users']),
        ]);
    }
}