<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    // List users with search
    public function index(Request $request)
{
    $query = User::query();

    // Search by name/email
    if ($request->filled('search')) {
        $search = $request->search;

        // Check if search is "active" or "inactive" (case-insensitive)
        if (strtolower($search) == 'active') {
            $query->where('status', 1);
        } elseif (strtolower($search) == 'inactive') {
            $query->where('status', 0);
        } else {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
    }

    $users = $query->paginate(10);

    return view('users.index', compact('users'));
}

    // Toggle user status
   public function toggleStatus($id)
{
    $user = User::findOrFail($id);
    $user->status = !$user->status; // toggle 1 ↔ 0
    $user->save();

    return redirect()->back()->with('success', 'User status updated successfully!');
}

public function delete($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->back()->with('success', 'User deleted successfully!');
}

    // Export users to CSV
    public function exportCsv()
    {
        $users = User::all();
        $filename = "users.csv";

        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Name', 'Email', 'Status']);

        foreach($users as $user){
            fputcsv($handle, [$user->id, $user->name, $user->email, $user->status ? 'Active' : 'Inactive']);
        }

        fclose($handle);
        return Response::download($filename);
    }
}