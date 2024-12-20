<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use denis660\Centrifugo\Centrifugo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CentrifugoProxyController extends Controller
{
    public function connect(){

        return new JsonResponse([
            'result' => [
                'user' => (string) Auth::user()->id,
                'chats' =>  ["personal:#".Auth::user()->id],
            ]
        ]);
    }
}
