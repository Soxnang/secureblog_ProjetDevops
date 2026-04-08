<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Liste tous les posts publiés
    public function index()
    {
        $posts = Post::published()
                     ->with('user')
                     ->latest()
                     ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    // Formulaire de création
    public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create');
    }

    // Enregistre un nouveau post
    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'title'   => 'required|min:5|max:255',
            'content' => 'required|min:10',
        ]);

        $post = Post::create([
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']),
            'content'      => $validated['content'],
            'user_id'      => Auth::id(),
            'published'    => $request->has('published'),
            'published_at' => $request->has('published') ? now() : null,
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Article créé avec succès !');
    }

    // Affiche un post
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // Formulaire de modification
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    // Met à jour un post
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title'   => 'required|min:5|max:255',
            'content' => 'required|min:10',
        ]);

        $post->update([
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']),
            'content'      => $validated['content'],
            'published'    => $request->has('published'),
            'published_at' => $request->has('published')
                              ? ($post->published_at ?? now())
                              : null,
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Article mis à jour !');
    }

    // Supprime un post
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Article supprimé !');
    }
}
