<?php

namespace App\Http\Controllers;

use App\Message;
use App\Room;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{

    /**
     * RoomController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('room.list', [
            'rooms' => Room::whereNotIn('id', Auth::user()->rooms()->pluck('room_id')->toArray())->get(),
            'alreadyIn' => Auth::user()->rooms,
            'baneList' => Auth::user()->ownRooms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Auth::user()->rooms()->attach([
            'room_id' => $request->input('room_id')
        ]);

        return redirect('/room')->with('status', 'Saved');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $room = Room::create([
            'primary_user' => Auth::user()->id,
            'name' => $request->input('room_name'),
        ]);

        Auth::user()->rooms()->attach([
            'room_id' => $room->id
        ]);

        return redirect('/room')->with('status', 'Saved');
    }

    /**
     * Display the specified resource.
     *
     * @param Room $room
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Room $room)
    {
        $roomMessages = Message::where('room_id', $room->id)->with(['user'])->get();

        return view('room.room', [
            'room' => $room,
            'roomMessages' => $roomMessages
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auth::user()->rooms()->detach([
            'room_id' => $id
        ]);

        return redirect('/room')->with('status', 'Saved');
    }

    /**
     * Bane user.
     *
     * @param $roomId
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bane($roomId, $userId)
    {
        User::find($userId)->rooms()->updateExistingPivot($roomId, ['is_baned' => 1]);

        return redirect('/room/' . $roomId)->with('status', 'User Baned!');
    }

    /**
     * Unbane user.
     *
     * @param $roomId
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Unbane($roomId, $userId)
    {
        User::find($userId)->rooms()->updateExistingPivot($roomId, ['is_baned' => 0]);

        return redirect('/room')->with('status', 'User UnBaned!');
    }
}
