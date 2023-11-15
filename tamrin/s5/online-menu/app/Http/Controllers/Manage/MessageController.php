<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Message::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return Message::hydrate(DB::select('select * from messages'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message): Message
    {
        return $message;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message): JsonResponse
    {
        DB::delete('delete from messages where id = ?', [$message->id]);

        return response()->json(['message' => "You've deleted the message successfully."]);
    }
}
