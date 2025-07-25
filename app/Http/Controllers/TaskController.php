<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['store'])) {
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

        $user = auth()->user();

        $query = Task::with('board');

        if (!$user || !$user->is_admin) {
            $query->where('assignee_id', $user->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->with('board')->orderBy('due_date')->paginate(12);

        $tasks->getCollection()->transform(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'due_date' => $task->due_date,
                'board_name' => $task->board->title ?? null,
                'due_in' => $task->due_date ? Carbon::now()->diffInDays(Carbon::parse($task->due_date), false) : null,
                'board_id' => $task->board_id,
                'assignee_id' => $task->assignee_id
            ];
        });

        return response()->json($tasks);
    }

    public function reminders()
    {
        $user = auth()->user();

        $tasksQuery = Task::with('board')
            ->whereIn('status', ['to-do', 'in-progress'])
            ->whereNotNull('due_date')
            ->orderBy('due_date')
            ->limit(10);

        if (!$user->is_admin) {
            $tasksQuery->where('assignee_id', $user->id);
        }

        $tasks = $tasksQuery->get();

        $tasks = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'due_date' => $task->due_date,
                'board_name' => $task->board->title ?? null,
                'due_in' => $task->due_date ? Carbon::now()->diffInDays(Carbon::parse($task->due_date), false) : null,
                'board_id' => $task->board_id,
                'assignee_id' => $task->assignee_id
            ];
        });

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'due_date' => 'required|date|after:today',
            'assignee_id' => 'required|integer',
            'board_id' => 'required|integer',
            'creator_id' => 'required|integer',
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'due_date' => 'required|date|after:today',
            'assignee_id' => 'required|integer',
            'board_id' => 'required|integer',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function show($id)
    {
        $task = Task::with(['board', 'assignee'])->findOrFail($id);

        return response()->json([
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'due_date' => $task->due_date,
            'status' => $task->status,
            'board_id' => $task->board_id,
            'assignee_id' => $task->assignee_id,
            'board_name' => $task->board->title ?? null,
            'due_in' => $task->due_date ? Carbon::now()->diffInDays(Carbon::parse($task->due_date), false) : null,
        ]);
    }
}
