<?php

namespace App\Http\Controllers;
use App\Models\Board;

use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $boards = Board::orderBy('id')->paginate(10);

        return response()->json($boards);
    }
}
