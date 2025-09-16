<?php

use App\Events\MessageSent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('home');
});
Route::get('/realtime-chat', function () {
    return view('realtimeChat');
});

// Chat page route
Route::get('/chat', function () {
    return view('chat');
});


// Send message API
// routes/web.php
Route::post('/send-message', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'message' => 'required|string|max:500',
        'user' => 'required|string|max:100'
    ]);

    $user = $request->input('user');
    $message = $request->input('message');

    \Log::info('💬 Sending chat message', [
        'user' => $user,
        'message' => $message
    ]);

    try {
        // Broadcast the message
        broadcast(new App\Events\MessageSent($user, $message));

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => [
                'user' => $user,
                'message' => $message,
                'timestamp' => now()->toISOString()
            ]
        ]);
    } catch (\Exception $e) {
        \Log::error('❌ Error broadcasting message: ' . $e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to send message',
            'error' => $e->getMessage()
        ], 500);
    }
});

// routes/web.php
Route::get('/test-broadcast', function () {
    \Log::info('=== BROADCAST TEST STARTED ===');

    try {
        $user = (object) ['name' => 'Test User'];
        $message = 'Test message at ' . now();

        \Log::info('Creating MessageSent event', [
            'user' => $user->name,
            'message' => $message
        ]);

        $event = new MessageSent($user, $message);

        \Log::info('Broadcasting event...');
        // $event->broadcastOn();
        broadcast($event);

        \Log::info('✅ Broadcast completed successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Check storage/logs/laravel.log for details'
        ]);

    } catch (\Exception $e) {
        \Log::error('❌ Broadcast failed: ' . $e->getMessage(), [
            'exception' => $e
        ]);

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// routes/web.php
Route::get('/test-broadcast-2', function () {
    \Log::info('🚀 Test broadcast route called');

    try {
        $user = 'Test User';
        $message = 'Test message at ' . now();

        \Log::info('Creating event...');
        $event = new App\Events\MessageSent($user, $message);

        \Log::info('About to broadcast...');
        broadcast($event);

        \Log::info('✅ Broadcast call completed');

        return response()->json([
            'status' => 'success',
            'message' => 'Event broadcasted',
            'user' => $user,
            'text' => $message
        ]);
    } catch (\Exception $e) {
        \Log::error('❌ Broadcast error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
});