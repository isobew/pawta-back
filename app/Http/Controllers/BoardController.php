<?php

namespace App\Http\Controllers;
use App\Models\Board;

use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Board::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $boards = $query->orderBy('id')->paginate(10);

        return response()->json($boards);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $board = Board::create([
            'title' => $request->title,
        ]);

        return response()->json($board, 201);
    }

    public function update(Request $request, $id)
    {
        $board = Board::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $board->title = $request->title;
        $board->save();

        return response()->json($board);
    }

    public function delete($id)
    {
        $board = Board::findOrFail($id);
        $board->delete();

        return response()->json(null, 204);
    }

    public function show($id)
    {
        $board = Board::with('tasks')->find($id);

        if (!$board) {
            return response()->json(['message' => 'Board not found.'], 404);
        }

        return response()->json([
            'board' => $board,
            'tasks' => $board->tasks
        ]);
    }
}
