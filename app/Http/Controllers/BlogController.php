<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('pages.blog.index', [
            'posts' => Post::with(['category', 'tags'])->published()->latest('published_at')->get(),
            'tags' => Tag::ordered()->get(),
        ]);
    }

    public function show(Post $post): View
    {
        $post->load(['category', 'tags']);

        $relatedPosts = Post::with('category')
            ->published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('pages.blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
