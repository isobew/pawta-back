<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search'); // ?search=texto

        $query = Task::where('assignee_id', auth()->id());

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->orderBy('due_date')
                       ->paginate(10);

        return response()->json($tasks);
    }

    public function reminders()
    {
        $tasks = Task::where('assignee_id', auth()->id())
            ->whereIn('status', ['to-do', 'in-progress'])
            ->whereNotNull('due_date')
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        return response()->json($tasks);
    }

}
