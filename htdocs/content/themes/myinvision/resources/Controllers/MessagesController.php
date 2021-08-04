<?php

namespace App\Http\Controllers;

use Theme\Helpers\AuthUser;
use Theme\Models\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class MessagesController extends Controller
{
    public function getAllThreads()
    {
        $threads = Thread::getAllLatest()->get();
        return response(compact($threads));
    }
    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function showThread($id)
    {
        $threads = Thread::getAllLatest()->get();

        try {
            $thread = Thread::find($id);
        } catch (ModelNotFoundException $e) {
            return response('The thread with ID: ' . $id . ' was not found.', 404);
        }

        $userId = Auth::id();

        $thread->markAsRead($userId);

        $query = $thread->messages()
            ->with('user')
            ->latest();

        $messages = $query->get();
//        $messages->load('user:id,surname,first_name,middle_name');

        return response(compact($threads, $thread, $messages));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store()
    {
        $input = Request::all();

        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        $threads = Thread::getAllLatest()->get();

        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => AuthUser::currentUserId(),
            'last_read' => new Carbon,
        ]);

        if (Request::has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }

        $query = $thread->messages()
            ->with('user')
            ->latest();


        $messages = $query->get();

        return response(compact($messages, $thread, $threads));
    }

    public function addUsers(Request $request, $id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
           return response('The thread with ID: ' . $id . ' was not found.', 404);
        }

        if ($request->has('recipients')) {
            $participant = explode(',', $request->input('recipients'));
            $thread->addParticipant($participant);
        } else {
            abort(404);
        }

        return response('OK');
    }

    public function storeMessage(Thread $thread, \Illuminate\Http\Request $request)
    {
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => AuthUser::currentUserId(),
            'body' => $request->get('body'),
        ]);

        return response('OK');
    }
}