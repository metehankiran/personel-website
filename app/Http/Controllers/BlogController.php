<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('pages.blog.index', [
            'posts' => Post::with(['category', 'tags'])->published()->latest('published_at')->get(),
        ]);
    }

    public function show(Post $post): View
    {
        $post->load(['category', 'tags']);

        return view('pages.blog.show', ['post' => $post]);
    }
}
