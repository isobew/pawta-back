<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    public string $fileName = 'tasks.csv';

    public function collection()
    {
        $user = auth()->user();
        $query = Task::with(['board', 'assignee']);

        if (!$user || !$user->is_admin) {
            $query->where('assignee_id', $user->id);
        }

        return $query->get();
    }

    public function map($task): array
    {
        return [
            $task->id,
            $task->title,
            $task->description,
            $task->status,
            $task->due_date,
            $task->created_at,
            $task->assignee?->name ?? '—',
            $task->board?->title ?? '—',
        ];
    }

    public function headings(): array
    {
        return ['ID', 'Title', 'Description', 'Status', 'Due Date', 'Created At', 'Assignee', 'Board'];
    }

    public function toResponse($request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download($this, $this->fileName);
    }
}
