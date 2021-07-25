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
            'thread_id' => 'required|exists:threads,id',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
            return response(400);
        }

        $message = ThreadMessage::create([
            'user_id' => wp_get_current_user()->ID,
            'thread_id' => $request->get('thread_id'),
            'body' => $request->get('body')]);

        if ($message) {
            return Thread::find($request->get('thread_id'))->messages();
        }
        return response(400);
    }
}