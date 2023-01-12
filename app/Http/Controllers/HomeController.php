<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $users = User::query()->where('id','!=',auth()->user()->id)->get();
        $conversations = auth()->user()->conversations();

        return view('home',compact('users','conversations'));
    }
}
