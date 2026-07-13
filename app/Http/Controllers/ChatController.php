<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $workspaceId = $user->current_workspace_id;

        $conversations = ChatConversation::where('workspace_id', $workspaceId)
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->with([
                'participants:id,name,avatar',
                'latestMessage.sender:id,name',
            ])
            ->latest()
            ->get()
            ->map(function ($conv) use ($user) {
                $latest = $conv->latestMessage->first();
                return [
                    'id'          => $conv->id,
                    'type'        => $conv->type,
                    'name'        => $conv->display_name,
                    'participants'=> $conv->participants,
                    'last_message'=> $latest ? [
                        'message'    => $latest->message,
                        'sender_name'=> $latest->sender->name ?? '',
                        'created_at' => $latest->created_at,
                    ] : null,
                    'unread_count'=> $conv->unreadCountFor($user->id),
                ];
            });

        // Users in the same workspace for starting new conversations
        $workspaceUsers = User::whereHas('workspaces', fn($q) => $q->where('workspace_id', $workspaceId))
            ->where('id', '!=', $user->id)
            ->select('id', 'name', 'avatar')
            ->get();

        $projects = Project::where('workspace_id', $workspaceId)
            ->select('id', 'title')
            ->get();

        return Inertia::render('chat/index', [
            'conversations' => $conversations,
            'workspaceUsers' => $workspaceUsers,
            'projects' => $projects,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $workspaceId = $user->current_workspace_id;

        $request->validate([
            'type'         => 'required|in:direct,group,project',
            'name'         => 'nullable|string|max:255',
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
            'project_id'   => 'nullable|exists:projects,id',
        ]);

        // For direct messages prevent duplicates
        if ($request->type === 'direct') {
            $otherId = $request->participant_ids[0];

            $existing = ChatConversation::where('workspace_id', $workspaceId)
                ->where('type', 'direct')
                ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
                ->whereHas('participants', fn($q) => $q->where('user_id', $otherId))
                ->first();

            if ($existing) {
                return redirect()->route('chat.index', ['conversation' => $existing->id]);
            }
        }

        $conversation = ChatConversation::create([
            'workspace_id' => $workspaceId,
            'project_id'   => $request->project_id,
            'type'         => $request->type,
            'name'         => $request->name,
            'created_by'   => $user->id,
        ]);

        // Add creator + selected participants
        $participantIds = array_unique(array_merge([$user->id], $request->participant_ids));
        $conversation->participants()->attach($participantIds);

        return redirect()->route('chat.index', ['conversation' => $conversation->id]);
    }

    public function messages(Request $request, ChatConversation $conversation)
    {
        $user = auth()->user();

        // Ensure user is a participant
        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('sender:id,name,avatar')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id'         => $m->id,
                'message'    => $m->message,
                'user_id'    => $m->user_id,
                'sender'     => $m->sender,
                'created_at' => $m->created_at,
                'is_mine'    => $m->user_id === $user->id,
            ]);

        // Mark messages as read for current user
        $conversation->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);

        // Build participants_read map: {user_id => last_read_at} for other participants
        $participantsRead = $conversation->participants()
            ->where('user_id', '!=', $user->id)
            ->get()
            ->mapWithKeys(fn($p) => [
                (string) $p->id => $p->pivot->last_read_at?->toISOString(),
            ]);

        return response()->json([
            'messages'          => $messages,
            'participants_read' => $participantsRead,
        ]);
    }

    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        $user = auth()->user();

        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id'         => $user->id,
            'message'         => $request->message,
        ]);

        $message->load('sender:id,name,avatar');

        return response()->json([
            'id'         => $message->id,
            'message'    => $message->message,
            'user_id'    => $message->user_id,
            'sender'     => $message->sender,
            'created_at' => $message->created_at,
            'is_mine'    => true,
        ]);
    }
}
