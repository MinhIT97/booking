<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\SendMessageRequest;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends Controller
{
    public function index(string $userId): AnonymousResourceCollection
    {
        $currentUserId = request()->user()->id;

        $messages = Message::with(['sender', 'receiver', 'property'])
            ->where(function ($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $currentUserId)->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $userId)->where('receiver_id', $currentUserId);
            })->orderBy('created_at', 'asc')->get();

        return MessageResource::collection($messages);
    }

    public function store(SendMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['sender_id'] = $request->user()->id;

        $message = Message::create($data);

        $message->load(['sender', 'receiver', 'property']);

        // Broadcast event for Laravel Reverb
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => new MessageResource($message)
        ], 201);
    }
}
