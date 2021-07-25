@extends('layouts.main')
@section('main_content')
    <div>
        <div id="app">
            <div>
                @foreach($threads as $thread)
                <form method="post" id="add-thread-form" action="{{ route('invite_participant', $thread->id) }}">
                    @csrf
                    <select name="participants" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->ID }}">{{ $user->user_login }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="_token" v-bind:value="token">
                    <input type="submit">
                </form>
                @endforeach
                <chat-component
                        token="{{ @csrf_token() }}"
                >
                </chat-component>
            </div>
        </div>
    </div>
@endsection