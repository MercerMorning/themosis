<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Theme\Models\Thread;
use Theme\Models\ThreadMessage;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(400);
        }

        $thread = ThreadMessage::create(['body' => $request->get('body')]);


        $userThreads = User::find(wp_get_current_user()->ID)->threads();

        $isSuccess = ThreadParticipant::create(['thread_id' => $thread->id,
            'user_id' => wp_get_current_user()->ID
        ]);
        if ($isSuccess) {
            return response($userThreads);
        }
        return response(400);
    }
}