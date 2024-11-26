<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentHistory;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function index()
    {
        $comments = Comment::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'content', 'user_id', 'created_at']);

        return response()->json([
            'message' => 'Comments retrieved successfully.',
            'comments' => $comments,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment created successfully.',
            'comment' => $comment,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        // Verificar se o comentário pertence ao usuário autenticado
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'You can only edit your own comments.'], 403);
        }

        // Salvar o histórico antes de atualizar
        CommentHistory::create([
            'comment_id' => $comment->id,
            'content' => $comment->content,
            'edited_at' => now(),
        ]);

        // Atualizar o comentário
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'message' => 'Comment updated successfully.',
            'comment' => $comment,
        ]);
    }

    public function history($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        if ($comment->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['message' => 'You can only view the history of your own comments.'], 403);
        }

        $history = CommentHistory::where('comment_id', $id)->get();

        return response()->json([
            'comment' => $comment,
            'history' => $history,
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        if ($comment->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['message' => 'You can only delete your own comments.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
