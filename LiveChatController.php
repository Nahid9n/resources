<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\LiveMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LiveChatController extends Controller
{

    public function check($sid)
    {
        $session = ChatSession::find($sid);

        if ($session) {
            return response()->json([
                'success' => true,
                'status' => $session->status   // "open" / "closed"
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    // step B: create session from form
    public function start(Request $request) {

        $request->validate([
            'guest_name'  => 'required|string|max:255',
            'guest_phone' => 'required',
            'message' => 'nullable|string',
        ]);

        // create chat session
        $session = ChatSession::create([
            'guest_name'  => $request->guest_name,
            'guest_phone' => $request->guest_phone,
            'status'      => 'open',
            'last_activity_at' => now(),
        ]);

        // save first message (if any)
        if ($request->message) {
            LiveMessage::create([
                'chat_session_id' => $session->id,
                'sender_type'     => 'guest',
                'message'         => $request->message,
            ]);
        }

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'guest_name' => $session->guest_name,
        ]);
    }


    // AJAX: send message
    public function send(Request $r) {
        $r->validate([
            'sid'     => 'required',
            'message' => 'required|string|max:500',
        ]);

        $msg = LiveMessage::create([
            'chat_session_id' => $r->sid,
            'sender_type' => 'guest',
            'message' => $r->message,
        ]);

        ChatSession::where('id',$r->sid)->update(['last_activity_at'=>now()]);
        return response()->json($msg);
    }

    public function fetch(Request $request, string $sid) {
        // Ensure sid is numeric
        $chatSessionId = (int) $sid;

        if (!$chatSessionId) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid chat session.'
            ], 400);
        }

        $lastId = (int) $request->query('after', 0);

        // Mark admin -> guest messages as seen
        LiveMessage::where('chat_session_id', $chatSessionId)
            ->where('sender_type', 'admin')
            ->where('seen_by_guest', false)
            ->update(['seen_by_guest' => true]);

        // Fetch messages
        $messages = LiveMessage::where('chat_session_id', $chatSessionId)
            ->when($lastId > 0, function($query) use ($lastId) {
                $query->where('id', '>', $lastId);
            })
            ->orderBy('id')
            ->get(['id', 'sender_type', 'message', 'created_at']);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }


    // Typing indicator: guest → set typing flag (expires automatically)
    public function typing(Request $r, string $sid) {
        $isTyping = filter_var($r->input('typing', false), FILTER_VALIDATE_BOOLEAN);
        if ($isTyping) {
            Cache::put("typing:$sid:guest", true, now()->addSeconds(5));
        } else {
            Cache::forget("typing:$sid:guest");
        }
        return response()->json(['ok'=>true]);
    }

    public function typingStatus(string $sid) {
        return response()->json([
            'admin' => Cache::has("typing:$sid:admin"),
            'guest' => Cache::has("typing:$sid:guest"),
        ]);
    }
}
