<?php

namespace Theme\Controllers;

use Illuminate\Routing\Controller;
use Symfony\Component\Console\Helper\Helper;
use Theme\Helpers\AuthUser;
use Theme\Models\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Theme\Services\UserPrepareService;

class MessagesController extends Controller
{
    public function showChat()
    {
        global $post;

        $threads = $this->getAllThreads();
        $threads->map(function ($thread) {
            $lastMessageData = $thread
                ->messages()
                ->get()
                ->last();
            $thread->lastMessage =
                UserPrepareService::threadPresenterLastMessage(
                    $lastMessageData->user_id, $lastMessageData->body
                );
            $thread->datetime = Carbon::parse($thread->updated_at)->format('d/m/Y');
            return $thread;
        });
        $threads = $threads->sortBy('updated_at');
        $currentUserData = UserPrepareService::currentUserPresenter();
        $chat = view('front.chat', [
            'currentUser' => json_encode($currentUserData),
            'threads' => json_encode($threads),
        ])->render();
        return $chat;
    }

    public function getAllThreads()
    {
        $threads =  Thread::forUser(AuthUser::currentUserId())
            ->latest('updated_at')
            ->get();;
        return $threads;
    }
    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function showThread(Request $request)
    {
        $id = $request->get('id');
        $threads = $this->getAllThreads();

        try {
            $thread = Thread::find($id);
        } catch (ModelNotFoundException $e) {
            return response('The thread with ID: ' . $id . ' was not found.', 404);
        }

        $userId = Auth::id();

        $thread->markAsRead($userId);

        $query = $thread->messages()
//            ->with('user')
            ->latest();

        $messages = $query->get();
//        $messages->load('user:id,surname,first_name,middle_name');
        return [
            'threads' => $threads,
            'currentThread' => $thread,
            'threadMessages' => $messages
        ];
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        $threads = Thread::getAllLatest()->get();
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => AuthUser::currentUserId(),
            'last_read' => new Carbon,
        ]);

        if ($request->has('recipients')) {
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