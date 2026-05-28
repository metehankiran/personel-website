<?php

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListPosts::class)->assertOk();
});

test('posts appear in the table', function () {
    $posts = Post::factory()->count(3)->create();

    Livewire::test(ListPosts::class)
        ->assertCanSeeTableRecords($posts);
});

test('post can be created as draft', function () {
    $category = Category::factory()->create();

    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => 'Pattern Matching in Laravel',
            'slug' => 'pattern-matching-laravel',
            'excerpt' => 'A short note.',
            'body' => '<p>Long body content.</p>',
            'category_id' => $category->id,
            'is_published' => false,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $post = Post::firstWhere('slug', 'pattern-matching-laravel');
    expect($post)->not->toBeNull();
    expect($post->is_published)->toBeFalse();
    expect($post->category_id)->toBe($category->id);
});

test('post can be created with tags attached', function () {
    $category = Category::factory()->create();
    $tag1 = Tag::factory()->create();
    $tag2 = Tag::factory()->create();

    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => 'Tagged Post',
            'slug' => 'tagged-post',
            'body' => '<p>Content.</p>',
            'category_id' => $category->id,
            'tags' => [$tag1->id, $tag2->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $post = Post::firstWhere('slug', 'tagged-post');
    expect($post->tags()->pluck('tags.id')->toArray())->toEqualCanonicalizing([$tag1->id, $tag2->id]);
});

test('title is required', function () {
    Livewire::test(CreatePost::class)
        ->fillForm(['title' => null])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

test('category is required', function () {
    Livewire::test(CreatePost::class)
        ->fillForm(['category_id' => null])
        ->call('create')
        ->assertHasFormErrors(['category_id' => 'required']);
});

test('slug must be unique', function () {
    Post::factory()->create(['slug' => 'taken']);
    $category = Category::factory()->create();

    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => 'Other',
            'slug' => 'taken',
            'body' => '<p>x</p>',
            'category_id' => $category->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

test('post can be edited', function () {
    $post = Post::factory()->create(['title' => 'Old']);

    Livewire::test(EditPost::class, ['record' => $post->getRouteKey()])
        ->fillForm(['title' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($post->refresh()->title)->toBe('New');
});

test('post can be deleted from edit page', function () {
    $post = Post::factory()->create();

    Livewire::test(EditPost::class, ['record' => $post->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});
