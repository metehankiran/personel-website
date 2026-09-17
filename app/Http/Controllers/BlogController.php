<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('pages.blog.index', [
            'posts' => Post::with(['category', 'tags'])->published()->latest('published_at')->get(),
            ...$this->sidebar(),
            'activeCategory' => null,
            'activeTag' => null,
        ]);
    }

    public function category(Category $category): View
    {
        return view('pages.blog.index', [
            'posts' => Post::with(['category', 'tags'])->published()->where('category_id', $category->id)->latest('published_at')->get(),
            ...$this->sidebar(activeCategory: $category),
            'activeCategory' => $category,
            'activeTag' => null,
        ]);
    }

    public function tag(Tag $tag): View
    {
        return view('pages.blog.index', [
            'posts' => Post::with(['category', 'tags'])->published()->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id))->latest('published_at')->get(),
            ...$this->sidebar(activeTag: $tag),
            'activeCategory' => null,
            'activeTag' => $tag,
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->isVisibleToCurrentVisitor(), 404);

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

    /**
     * Filters that lead somewhere: categories and tags with published posts, plus the one
     * being viewed so its own listing never loses its highlighted pill.
     *
     * @return array{categories: Collection<int, Category>, tags: Collection<int, Tag>}
     */
    private function sidebar(?Category $activeCategory = null, ?Tag $activeTag = null): array
    {
        $withActive = fn (Collection $items, Category|Tag|null $active): Collection => $active === null || $items->contains($active)
            ? $items
            : $items->push($active)->sortBy('sort_order')->values();

        return [
            'categories' => $withActive(Category::withPublishedPosts()->ordered()->get(), $activeCategory),
            'tags' => $withActive(Tag::withPublishedPosts()->ordered()->get(), $activeTag),
        ];
    }
}
