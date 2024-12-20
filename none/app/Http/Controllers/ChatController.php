<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use denis660\Centrifugo\Centrifugo;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class ChatController extends Controller
{
    private Centrifugo $centrifugo;

    public function __construct(Centrifugo $centrifugo)
    {
        $this->centrifugo = $centrifugo;
    }

    public function show(int $id)
    {
        $chats = Chat::with('users')->get();
        $chat = Chat::with('users')->find($id);
        return view('chat', [
            'chats' => $chats,
            'currChat' => $chat,
            'isJoin' => $chat->users->contains('id', Auth::user()->id),
        ]);
    }

    public function join(int $id)
    {
        $chat = Chat::findOrFail($id);
        $chat->users()->attach(Auth::user()->id);
        
        return redirect()->route('chat.show', $id);
    }


    public function sendMessage(Request $request)
    {
        $data = [
            'user' => $request->user()->name,
            'message' => $request->message,
        ];

        $this->centrifugo->publish('chat_channel', $data);
        return response()->json(['success' => true]);
    }
}
