<?php

namespace App\Http\Controllers;
use App\Events\UserRegistered;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $user = User::create($request->all());

        // Trigger Pusher event
        broadcast(new UserRegistered($user))->toOthers();

        return response()->json(['success' => true]);
    }
}
