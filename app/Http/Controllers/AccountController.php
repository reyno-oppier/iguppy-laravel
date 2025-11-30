<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    // app/Http/Controllers/AccountController.php

    public function edit()
    {
        $user = auth()->user();
        return view('account.edit', compact('user'));
    }
    
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
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('avatar'), $filename);
            $user->avatar = $filename;
        }
    
        $user->save();
    
        return redirect()->route('account.edit')->with('success', 'Profil berhasil diperbarui!');
    }
    
}
