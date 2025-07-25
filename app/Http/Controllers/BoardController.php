<?php

namespace App\Http\Controllers;
use App\Models\Board;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BoardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['store', 'update', 'delete'])) {
                if (Gate::denies('is-admin')) {
                    return response()->json(['message' => 'Only administrators can perform this action.'], 403);
                }
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Board::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $boards = $query->orderBy('id')->paginate(14);

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
        $user = auth()->user();

        $board = Board::find($id);

        if (!$board) {
            return response()->json(['message' => 'Board not found.'], 404);
        }

        if ($user && $user->is_admin) {
            $tasks = $board->tasks;
        } else {
            $tasks = $board->tasks()->where('assignee_id', $user->id)->get();
        }

        $tasks = $tasks->map(function ($task) use ($board) {
            $task->board_name = $board->title ?? null;
            $task->due_in = $task->due_date ? now()->diffInDays($task->due_date, false) : null;
            return $task;
        });

        return response()->json([
            'board' => $board,
            'tasks' => $tasks
        ]);
    }
}
