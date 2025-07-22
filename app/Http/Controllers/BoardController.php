<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $boards = Board::orderBy('title')->paginate(10);

        return response()->json($boards);
    }
}
