<?php

namespace App\Http\Controllers;

use App\Exports\TasksExport;
use Maatwebsite\Excel\Facades\Excel;

class TaskExportController extends Controller
{
    public function export()
    {
        return Excel::download(new TasksExport, 'tasks.csv');
    }
}
