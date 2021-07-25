<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        $threads = User::find(wp_get_current_user()->ID)->threads();
        $users = \Theme\Models\User::all();

        return view('front.chat', [
            'threads' => $threads,
            'users' => $users,
        ]);
    }
}