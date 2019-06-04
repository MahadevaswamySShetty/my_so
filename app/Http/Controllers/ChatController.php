<?php

namespace App\Http\Controllers;

use App\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
{
    public function chat()
    {
        $chat = Chat::with('users')->get();
        return view('chat', compact('chat'));
    	
    }

    public function  sendMessage(Request $request)
    {
    	$redis = Redis::connection();
    	$data = ['message' => $request->get('message'), 'user' => $request->get('user')];
    	$redis->publish('message', json_encode($data));
    	return response()->json([]);
    }

    public function group_chat()
    {
        $chat = Chat::with('users')->get();
        return view('message.group_chat', compact('chat'));
    }

    public function group_chat_store(Request $request)
    {
        $chat = new Chat;
        $chat['user_id'] = Auth::user()->id;
        $chat['message'] = $request->get('message');
        $chat['is_read'] = 0;
        $chat->save();
        $redis = Redis::connection();
        $dd['data'] = Chat::with('users')->where('id','=',$chat->id)->first();
        $dd['time'] = $chat->created_at->diffForHumans();
        $redis->publish('message', json_encode($dd));
        return response()->json([]);
    }
}
