@extends('layouts.main')
@section('main_content')
    <div>
        <div id="app">
            <div>
                @foreach($users as $user)
                    <form method="post" id="add-thread-form" action="{{ route('send_message_to_user') }}">
                        <input type="hidden" name="user_id" value="{{ $user->ID }}">
                        <input name="body" type="text">
                        <input type="submit">
                    </form>
                @endforeach
{{--                @foreach($threads as $thread)--}}
{{--                <form method="post" id="add-thread-form" action="{{ route('invite_participant') }}">--}}
{{--                    <input type="hidden" name="thread_id" value="{{ $thread->id }}">--}}
{{--                    <select name="participants" multiple>--}}
{{--                        @foreach($users as $user)--}}

{{--                            @if(isset($thread_participants[$thread->id])--}}
{{--                                && !in_array($user->id, $thread_participants[$thread->id]))--}}
{{--                                <option value="{{ $user->ID }}">{{ $user->user_login }}</option>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    <input type="hidden" name="_token" v-bind:value="token">--}}
{{--                    <input type="submit">--}}
{{--                </form>--}}
{{--                @endforeach--}}
                <chat-component
                        token="{{ @csrf_token() }}"
                >
                </chat-component>
            </div>
        </div>
    </div>
@endsection