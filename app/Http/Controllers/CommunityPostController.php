<?php

namespace App\Http\Controllers;

use App\Models\CommunityReaction;
use App\Models\CommunityPost;
use Illuminate\Http\Request;

class CommunityPostController extends Controller
{
    public function index()
    {
        $posts = CommunityPost::with('user', 'reactions')
                    ->latest()
                    ->get();
        
        $recent = CommunityPost::with('user')->latest()->take(3)->get();

        return view('community', compact('posts', 'recent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        CommunityPost::create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return back();
    }

    public function react(CommunityPost $post)
    {
        CommunityReaction::firstOrCreate([
            'post_id' => $post->id,
            'user_id' => auth()->id()
        ]);

        return back();
    }
    
    public function update(Request $request, $id)
    {
        $post = CommunityPost::findOrFail($id);

        // Only owner can update
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post->update(['content' => $request->content]);

        return back()->with('success', 'Note updated successfully!');
    }

    public function destroy($id)
    {
        $post = CommunityPost::findOrFail($id);

        // Only owner can delete
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $post->delete();
        return back()->with('success', 'Note deleted successfully!');
    }

}

