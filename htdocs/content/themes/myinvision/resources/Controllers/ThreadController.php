<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Theme\Models\Thread;
use Theme\Models\ThreadParticipant;

class ThreadController extends Controller
{
    public function add(Request $request)
    {
        Thread::create(['subject' => $request->get('name')]);
        return csrf_token();
    }

    public function inviteParticipant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thread_id' => 'required|exists:threads,id',
            'participants' => 'required|exists:users,ID',
        ]);

        if ($validator->fails()) {
            return response('','404');
        }

        return ThreadParticipant::create(['thread_id' => $request->get('thread_id'),
            'user_id' => $request->get('participants')
        ]);
    }
}