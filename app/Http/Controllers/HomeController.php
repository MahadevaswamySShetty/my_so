<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Conversation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index1()
    {
        $user = User::where('id','!=', Auth::user()->id)->get();
        $conversation = Conversation::where('user1','=', Auth::user()->id)->orWhere('user2','=',Auth::user()->id)->get();
        //return $conversation;
        ;
        return view('home', compact('user','conversation','chat'));
    }

    public function index()
    {
        $chat = Chat::with('users')->get();
        //return $chat;
        return view('home', compact('chat'));
    }
}
