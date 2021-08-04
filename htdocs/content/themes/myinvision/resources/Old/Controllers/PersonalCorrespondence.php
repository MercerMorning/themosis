<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Theme\Models\Thread;
use Theme\Models\ThreadMessage;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;

class PersonalCorrespondence extends Controller
{
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response('','404');
        }

        $thread = Thread::create(['subject' => ' ']);

        return ThreadMessage::create(['thread_id' => $thread->id,
            'user_id' => wp_get_current_user()->ID,
            'body' => $request->get('body')
        ]);
    }
}