<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        $threads = \Theme\Models\Thread::all();
        $users = \Theme\Models\User::all();
        return view('front.chat', [
            'threads' => $threads,
            'users' => $users
        ]);
    }
}