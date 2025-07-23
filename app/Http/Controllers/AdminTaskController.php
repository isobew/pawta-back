<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    public function users(Request $request)
    {
        $users = User::orderBy('id')->get();

        return response()->json($users);
    }
}
