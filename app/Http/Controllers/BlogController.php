<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('author')
            ->where('is_published', true)
            ->latest()
            ->paginate(9);

        return view('pages.blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::with(['author', 'tags'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(4)
            ->get();

        $recentPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.blog.show', compact('post', 'relatedPosts', 'recentPosts'));
    }
}
