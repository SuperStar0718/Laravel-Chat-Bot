<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Message;

class ChatGPTController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        Message::create([
            'content' => $request->input('userMessage'),
            'sender' => 'user'
        ]);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPEN_AI_API_KEY')
        ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $request->input('userMessage')
                    ]
                ],
                'temperature' => 0,
                'max_tokens' => 2048
            ])->body();
        Message::create([
            'content' => json_decode($response)->choices[0]->message->content,
            'sender' => 'ChatGPT'
        ]);

        return response()->json([
            'message' => json_decode($response)->choices[0]->message->content,
        ]);
    }

    public function getHistory(Request $request): JsonResponse
    {
        $messages = Message::all();
        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function deleteMessage(Request $request): JsonResponse
    {
        //find the message by content
        $message = Message::where('content', $request->input('message'))->first();
        
        $message->delete();
        return response()->json([
            'message' => 'Message deleted',
        ]);
    }
}
