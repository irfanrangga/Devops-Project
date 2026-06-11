<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getMessages()
    {
        $messages = Chat::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'messages' => $messages,
        ]);
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = Chat::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => false, // Karena ini dikirim oleh customer
        ]);

        return response()->json(['status' => 'success', 'data' => $message]);
    }
}
