<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Theme\Models\Thread;

class ThreadController extends Controller
{
    public function add(Request $request)
    {
        Thread::create(['subject' => $request->get('name')]);
        return csrf_token();
    }

    public function inviteParticipant(Request $request)
    {
        dd($request->all());
    }
}