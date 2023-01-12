<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function store(Request $request)
    {
        $c1 = Conversation::where('user1',auth()->user()->id)->where('user2',$request->user_id)->count();
        $c2 = Conversation::where('user2',auth()->user()->id)->where('user1',$request->user_id)->count();
        if($c1 != 0 || $c2 != 0)
            return redirect()->back();

        $c = new Conversation();
        $c->user1 = auth()->id();
        $c->user2 = $request->user_id;
        $c->save();

        return redirect()->back();
    }
    public function show(Conversation $conversation)
    {
        if($conversation->user1 == auth()->id() || $conversation->user2 == auth()->id())
            return view('conversations.show',compact('conversation'));

        return redirect()->back();
    }
}
