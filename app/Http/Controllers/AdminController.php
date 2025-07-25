<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getActionMethod(), ['index', 'users', 'updateUser', 'deleteUser'])) {
                if (Gate::denies('is-admin')) {
                    return response()->json(['message' => 'Only administrators can perform this action.'], 403);
                }
            }

            return $next($request);
        });
    }

    public function users(Request $request)
    {
        $users = User::orderBy('id')->get();

        return response()->json($users);
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->paginate(10);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'is_admin' => 'boolean',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
