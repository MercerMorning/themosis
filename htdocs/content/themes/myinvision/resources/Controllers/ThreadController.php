<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Theme\Models\Thread;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;

class ThreadController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(400);
        }

        $thread = Thread::create(['subject' => $request->get('name')]);
        $userThreads = User::find(wp_get_current_user()->ID)->threads();

        $isSuccess = ThreadParticipant::create(['thread_id' => $thread->id,
            'user_id' => wp_get_current_user()->ID
        ]);
        if ($isSuccess) {
            return response($userThreads);
        }
        return response(400);
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

    public function getAll()
    {

    }
}