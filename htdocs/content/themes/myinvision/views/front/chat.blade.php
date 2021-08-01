@extends('layouts.main')
@section('main_content')
        <div id="app">
                <chat-component
                        threads="{{ $threads }}"
                        users="{{ $users }}"
                >
                </chat-component>
            </div>
@endsection