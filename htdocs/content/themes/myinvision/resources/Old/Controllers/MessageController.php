<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Util\Filesystem;
use Theme\Models\Thread;
use Theme\Models\ThreadMessage;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;
use Theme\Services\ThreadsListService;


class MessageController extends Controller
{
    public function send(Request $request)
    {
        if ($request->has('image')) {
            $fileName = "file_" . Str::uuid();
            file_put_contents(get_template_directory() . '/storage/' . $fileName, $request->get('image'));
//            Storage::disk('theme')->put($fileName, $request->get('image'));
            ThreadMessage::create([
                'user_id' => wp_get_current_user()->ID,
                'is_file' => 1,
                'thread_id' => $request->get('thread_id'),
                'body' => get_template_directory_uri() . '/storage/' . $fileName]);
            $threadMessages = ThreadsListService::formateMessages($request->get('thread_id'));
            return $threadMessages;
        }

//        return json_encode($request->all());

        $validator = Validator::make($request->all(), [
            'thread_id' => 'required|exists:threads,id',
//            'file' => 'string|nullable',
            'body' => 'required|string',
        ]);



        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        $message = ThreadMessage::create([
            'user_id' => wp_get_current_user()->ID,
            'thread_id' => $request->get('thread_id'),
            'body' => $request->get('body')]);

        $threadMessages = ThreadsListService::formateMessages($request->get('thread_id'));

        if ($message) {
            return $threadMessages;
        }
        return response('', 400);
    }
}