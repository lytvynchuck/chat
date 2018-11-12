@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="header">Create own Romm</div>
                <form method="POST" action="/room">
                    @csrf
                    <input type="text" class="form-control" name="room_name" required>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Add room') }}
                    </button>
                </form>
                <div class="card">
                    <div class="card-header">{{ __('All Rooms') }}</div>
                    <div class="card-body">
                        <ul>
                            @foreach($rooms as $room)
                                <li>{{$room->name}}
                                        <form method="GET" action="/room/create">
                                            @csrf
                                            <input type="hidden" name="room_id" value="{{$room->id}}">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Enter') }}
                                            </button>
                                        </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{ __('Your Rooms') }}</div>
                    <div class="card-body">
                        <ul>
                            @foreach($alreadyIn as $room)
                                <li><a href="room/{{$room->id}}">{{$room->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{ __('Baned List') }}</div>
                    <div class="card-body">
                        <ul>
                            @foreach($baneList as $baned)
                                <h6>{{$baned->name}}</h6>
                                <ul>
                                    @foreach($baned->users as $bane)
                                        @if ($bane->pivot->is_baned == 1)
                                            <li>
                                                {{$bane->name}} |
                                                <a href="/room/{{$bane->pivot->room_id}}/unbane/{{$bane->id}}">UNBANE</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
