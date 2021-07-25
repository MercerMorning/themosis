<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        $threads = \Theme\Models\Thread::all();
        $users = \Theme\Models\User::all();

//        $threadParticipants = ThreadParticipant::all('user_id', 'thread_id');
//        $threadParticipants = $threadParticipants
//            ->groupBy('thread_id')
//            ->map(function ($item) {
//                return $item->pluck('user_id');
//            })
//            ->toArray();

        return view('front.chat', [
            'threads' => $threads,
            'users' => $users,
        ]);
    }
}