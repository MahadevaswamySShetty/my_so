<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redis;

class MessageController extends Controller
{
    public function store(Request $request)
    {
    	
    	$conversation = Conversation::find($request->get('conversation_id'));
    	if ($conversation->user1 == Auth::user()->id || $conversation->user2 == Auth::user()->id) {
    		$message = new Message;
    		$message['conversation_id'] = $request->get('conversation_id');
    		$message['user_id'] = Auth::user()->id;
    		$message['text'] = $request->get('text');
    		$message['is_read'] = 0;
    		$message->save();

    		$receiver_id = ($conversation->user1 == Auth::user()->id)? $conversation->user2 : $conversation->user1;
    		$data['conversation_id'] = $request->get('conversation_id');
    		$data['text'] = $request->get('text');
    		$data['client_id'] = $receiver_id;
    		
    		$redis = Redis::connection();
    		$redis->publish('message', json_encode($data));

    		return response()->json(true);
    	}else{
    		return response()->json("permission error");
    	}
    }
}
