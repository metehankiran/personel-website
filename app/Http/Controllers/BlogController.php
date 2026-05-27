<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
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
            'categories' => Category::ordered()->get(),
            'activeCategory' => null,
            'activeTag' => null,
        ]);
    }

    public function category(Category $category): View
    {
        return view('pages.blog.index', [
            'posts' => Post::with(['category', 'tags'])->published()->where('category_id', $category->id)->latest('published_at')->get(),
            'tags' => Tag::ordered()->get(),
            'categories' => Category::ordered()->get(),
            'activeCategory' => $category,
            'activeTag' => null,
        ]);
    }

    public function tag(Tag $tag): View
    {
        return view('pages.blog.index', [
            'posts' => Post::with(['category', 'tags'])->published()->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id))->latest('published_at')->get(),
            'tags' => Tag::ordered()->get(),
            'categories' => Category::ordered()->get(),
            'activeCategory' => null,
            'activeTag' => $tag,
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
