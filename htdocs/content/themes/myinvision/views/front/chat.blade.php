@extends('layouts.main')
@section('main_content')
        <div id="app">
                <chat-component
{{--                        currentUser="{{ $currentUser }}"--}}
                        threads="{{ $threads }}"
                        users="{{ $users }}"
                >
                </chat-component>
            </div>
@endsection