<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User; // <-- ADD THIS LINE to use the User model

class AccountController extends Controller
{
    // app/Http/Controllers/AccountController.php

    public function edit()
    {
        $user = auth()->user();
        
        // 1. Initialize $allUsers as an empty array/collection
        $allUsers = [];

        // 2. Check if the current user is the 'Admin'
        if ($user->name === 'Admin') {
            // 3. If Admin, fetch ALL users (excluding the Admin themselves might be wise, 
            //    but fetching all is simpler based on your request)
            $allUsers = User::all(); 
        }

        // Pass both $user (current) and $allUsers to the view
        return view('account.edit', compact('user', 'allUsers'));
    }
    
    // This method remains for self-service updates via route('account.update')
    public function update(Request $request)
    {
        $user = auth()->user();
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
    
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            // Assuming the 'avatar' directory is inside public_path() as per your original code
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Clean up old avatar if it exists
            if ($user->avatar && file_exists(public_path('avatar/' . $user->avatar)) && $user->avatar != 'default.png') {
                unlink(public_path('avatar/' . $user->avatar));
            }
            
            $file->move(public_path('avatar'), $filename);
            $user->avatar = $filename;
        }
    
        $user->save();
    
        return redirect()->route('account.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    public function adminUpdate(Request $request, User $user)
    {
        // 1. Validation for the specific user being updated
        $request->validate([
            'name' => 'required|string|max:255',
            // Exclude the current user's ID from the unique email check
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, 
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        // 2. Update user details
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Clean up old avatar if it exists
            if ($user->avatar && file_exists(public_path('avatar/' . $user->avatar)) && $user->avatar != 'default.png') {
                unlink(public_path('avatar/' . $user->avatar));
            }
            
            $file->move(public_path('avatar'), $filename);
            $user->avatar = $filename;
        }

        // 3. Save changes
        $user->save();

        return redirect()->route('account.edit')->with('success', "Profil pengguna **{$user->name}** berhasil diperbarui oleh Admin!");
    }
}