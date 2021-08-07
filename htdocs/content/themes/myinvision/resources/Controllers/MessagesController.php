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
use Theme\Services\ApiTokenController;
use Theme\Services\ApiTokenService;
use Theme\Services\UserPrepareService;

class MessagesController extends Controller
{
    public function showChat()
    {
        global $post;
        $userToken = ApiTokenService::update()['token'];
        $threads = $this->getAllThreads();
        $currentUserData = UserPrepareService::currentUserPresenter();
        $threadMessages = null;
        $currentThread = null;
        if (isset($_COOKIE['currentThreadId'])) {
            $threadInfo = $this->showThread(new Request(['id' => $_COOKIE['currentThreadId']]));
            $threadMessages = $threadInfo['threadMessages'];
            $currentThread = $threadInfo['currentThread'];
        }
        $chat = view('front.chat', [
            'userToken' => json_encode($userToken),
            'currentThread' => json_encode($currentThread),
            'threadMessages' => json_encode($threadMessages),
            'currentUser' => json_encode($currentUserData),
            'threads' => json_encode($threads),
        ])->render();
        return $chat;
    }

    public function getAllThreads($userId = null)
    {
        $threads =  Thread::forUser($userId ?? AuthUser::currentUserId())
            ->latest('updated_at')
            ->get();;
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
        $threads = $this->getAllThreads($request->get('user_id'));

        try {
            $thread = Thread::find($id);
        } catch (ModelNotFoundException $e) {
            return response('The thread with ID: ' . $id . ' was not found.', 404);
        }

        $userId = Auth::id() ?? $request->get('user_id');

        $thread->markAsRead($userId);

        $messages = $thread->messages()
//            ->with('user')
            ->latest()
            ->get()
            ->map(function ($message) {
                $message->date = $message->created_at->format('d.m.Y');
                return $message;
            })
            ->groupBy('date')
            ->map(function ($message) {
                $messages = $message->toArray();
                $formatedMessages = [];
                $messagesGroupKey = 0;
                $lastMessage = null;
                for ($i = 0; $i < count($messages); $i++) {
                    if ($i == 0) {
                        $lastMessage = $formatedMessages[$messagesGroupKey][] = $messages[$i];
                    } else {
                        if ($lastMessage['user_id'] !=  $messages[$i]['user_id']) {
                            $messagesGroupKey++;
                        }
                        $lastMessage = $formatedMessages[$messagesGroupKey][] = $messages[$i];
                    }
                }
                $formatedMessages = collect($formatedMessages)->map(function ($userMessages) {
                    return [
                        'messages' => $userMessages,
                        'user_info' =>
                        UserPrepareService::threadPresenterData(
                            $userMessages[0]['user_id']
                        )
                    ];
                });
                return $formatedMessages;
            });
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

    public function storeMessage(Request $request)
    {
        $userToken = User::find($request->get('user_id'))
            ->token()
            ->api_token;
        $requestToken = $request->get('token');

        if (!$userToken == $requestToken) {
            return response('', 400);
        }

        $message = Message::create([
            'thread_id' => $request->get('thread_id'),
            'user_id' => $request->get('user_id'),
            'body' => $request->get('body'),
        ]);

//        return $this->showThread(new Request(['user_id' => $request->get('user_id'),'id' => $request->get('thread_id')]))['threadMessages'];
        return $this->showThread(new Request(['user_id' => $request->get('user_id'),'id' => $request->get('thread_id')]));
    }
}