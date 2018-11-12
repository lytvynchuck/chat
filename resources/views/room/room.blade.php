@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="container">
            <form method="POST" action="/room/{{$room->id}}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="room_id" value="{{$room->id}}">
                <button type="submit" class="btn btn-primary">
                    {{ __('Exit room') }}
                </button>
            </form>
            Room: {{$room->name}}
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <private-chat :room="{{$room}}" :roommessages="{{$roomMessages}}" :user="{{Auth::user()}}"></private-chat>
        </div>
    @endif
@endsection
