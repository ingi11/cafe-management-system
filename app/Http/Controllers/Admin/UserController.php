<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Show the Staff Management Page
    public function index()
{
    
    $users = User::orderByRaw('id = ? DESC', [auth()->id()])
                ->orderBy('name', 'asc')
                ->get();

    return view('admin.users.index', compact('users'));
}

    // 2. Save New Staff
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,cashier',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
        ]);

        return back()->with('success', 'New staff member added to the system!');
    }

    // 3. Update existing Staff
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'role' => 'required|in:admin,cashier',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
        ]);

        // This only updates the fields that were actually sent in the request
        $user->update($request->only(['name', 'role', 'email']));

        return redirect()->back()->with('success', 'Staff member updated successfully!');
    }

    // 4. Delete Staff (Optional but recommended for your project)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting themselves!
        if (auth()->id() == $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', 'Staff member removed.');
    }
}