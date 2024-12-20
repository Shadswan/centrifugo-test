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

class ChatControllerTwo extends Controller
{
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
    
    public function TokenGenerate(Centrifugo $centrifugo){
        $token=$centrifugo->generateConnectionToken((string)Auth::id(),0,[
            'name'=> Auth::user()->name,
        ]);
        return response()->json([
            'token' => $token,
        ]);
    }
    public function canalsShow(Centrifugo $centrifugo){
        $canals=$centrifugo->channels();
        dd($canals);
    }
    public function send(Request $request, Centrifugo $centrifugo){
        $request->validate([
            'message' => 'required|string|max:255',
        ]);
        $data = $request->input('message');
        $centrifugo->publish('channel', ['message' => $data]);
        return response()->json([
            'message' => $data,
        ]);
    }
}
