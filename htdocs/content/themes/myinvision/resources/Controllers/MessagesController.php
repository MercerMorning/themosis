<?php

namespace Theme\Controllers;

use Carbon\Traits\Creator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\Helper;
use Theme\Helpers\AuthUser;
use Theme\Models\Attachment;
use Theme\Models\User;
use Carbon\Carbon;
use Theme\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Theme\Models\Thread;
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
        $threadMessages = NULL;
        $currentThread = NULL;
        $userForCreatePrivateChat = $this->getPrivateUsers();
        $users = User::query()
            ->whereNotIn('ID', [AuthUser::currentUserId()])
            ->get()
            ->map(function ($user) {
                return UserPrepareService::threadPresenterData($user->ID);
            })->toArray();
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
            'users' => json_encode($users),
            'privateUsers' => json_encode($userForCreatePrivateChat),
        ])->render();
//        dd($threads->toArray());
        return $chat;
    }

    private function getPrivateUsers()
    {
        return   User::query()
            ->where('ID', '!=', AuthUser::currentUserId())
            ->whereNotIn('ID', Thread::query()
                ->where('is_private', 1)
                ->get()
                ->map(function ($privateThread) {
                    return $privateThread
                        ->participants()
                        ->where('user_id', '!=', AuthUser::currentUserId())
                        ->first()->user_id;
                }))
            ->get()
            ->map(function ($user) {
                return UserPrepareService::threadPresenterData($user->ID);
            })->toArray();
    }

    public function getAllThreads($userId = NULL)
    {
        $threads = Thread::forUser($userId ?? AuthUser::currentUserId())
            ->latest('updated_at')
            ->get();;
        $threads->map(function ($thread) {
            if ($thread->is_private) {
                $thread->participants()
                    ->get()
                    ->pluck('user_id')
                    ->each(function ($participant) use ($thread) {
                        if ($participant !== (wp_get_current_user()->ID)) {
                            $userData = UserPrepareService::threadPresenterData($participant);
                            $thread->ava = $userData['ava'];
                            $thread->subject = $userData['first_name'] . ' ' . $userData['last_name'];
                        }
                    }
                    );
            }
            $lastMessageData = $thread
                ->messages()
                ->get()
                ->last();
            $thread->lastMessage =
                $lastMessageData ? UserPrepareService::threadPresenterLastMessage(
                    $lastMessageData->user_id, $lastMessageData->body
                ) : '';
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
            $thread = Thread::query()
                ->where('id', $id)
                ->get()
                ->map(function ($thread) {
                if ($thread->is_private) {
                    $thread->participants()
                        ->get()
                        ->pluck('user_id')
                        ->each(function ($participant) use ($thread) {
                            if ($participant !== (wp_get_current_user()->ID)) {
                                $userData = UserPrepareService::threadPresenterData($participant);
                                $thread->ava = $userData['ava'];
                                $thread->subject = $userData['first_name'] . ' ' . $userData['last_name'];
                            }
                        }
                        );
                }
                $lastMessageData = $thread
                    ->messages()
                    ->get()
                    ->last();
                $thread->lastMessage =
                    $lastMessageData ? UserPrepareService::threadPresenterLastMessage(
                        $lastMessageData->user_id, $lastMessageData->body
                    ) : '';
                $thread->datetime = Carbon::parse($thread->updated_at)->format('d/m/Y');
                return $thread;
            });
            $thread = $thread[0];
        } catch (ModelNotFoundException $e) {
            return response('The thread with ID: ' . $id . ' was not found.', 404);
        }

        $userId = Auth::id() ?? $request->get('user_id');

        $thread->markAsRead($userId);

        $messages = $thread->messages()
//            ->with('user')
            ->oldest()
            ->get()
            ->map(function ($message) {
                $message->date = $message->created_at->format('d.m.Y');
                return $message;
            })
            ->groupBy('date')
            ->map(function ($message) {
                $messages = Message::query()
                    ->whereIn('id', $message->pluck('id'))
                    ->with('attachment')
                    ->get()
                    ->map(function ($message) {
                        if ($message->attachment) {
                            $message->body = file_get_contents($message->attachment->path);
                            $message->isFile = true;

                        }
                        $message->datetime = Carbon::parse($message->created_at)->format('H:i');
                        return $message;
                    })
                    ->toArray();
                $formatedMessages = [];
                $messagesGroupKey = 0;
                $lastMessage = NULL;
                for ($i = 0; $i < count($messages); $i++) {
                    if ($i == 0) {
                        $lastMessage = $formatedMessages[$messagesGroupKey][] = $messages[$i];
                    } else {
                        if ($lastMessage['user_id'] != $messages[$i]['user_id']) {
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

        $threads = $this->getAllThreads();
        return response(['threads' => $threads]);
    }

    public function storePrivate(Request $request)
    {
        $input = $request->all();
        $thread = Thread::create([
            'subject' => '',
            'is_private' => 1,
        ]);

        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => AuthUser::currentUserId(),
            'last_read' => new Carbon,
        ]);

        if ($request->has('recipient')) {
            $thread->addParticipant($input['recipient']);
        }

        $query = $thread->messages()
            ->with('user')
            ->latest();


        $messages = $query->get();

        $threads = Thread::forUser(AuthUser::currentUserId())
            ->latest('updated_at')
            ->get();
        $thread = $this->showThread(new Request(['id' => $thread->id]));
        $thread['privateUsers'] =  $this->getPrivateUsers();
        return response($thread);
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

        $messageBody = $request->get('body');

        if ($request->get('file')) {
            $messageBody = ' ';
        }

        $message = Message::create([
            'thread_id' => $request->get('thread_id'),
            'user_id' => $request->get('user_id'),
            'body' => $messageBody,
        ]);

        if ($request->get('file')) {
            $fileName = "file_" . Str::uuid();
            file_put_contents(get_template_directory() . '/storage/' . $fileName, $request->get('file'));
            Attachment::create([
                'message_id' => $message->id,
                'path' => get_template_directory_uri() . '/storage/' . $fileName]);
        }


        return $this->showThread(new Request(['user_id' => $request->get('user_id'), 'id' => $request->get('thread_id')]));
    }
}