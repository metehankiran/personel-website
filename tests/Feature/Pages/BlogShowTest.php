<?php

declare(strict_types=1);

it('renders a blog post page for any slug', function () {
    $this->get(route('blog.show', 'laravel-paketleri'))
        ->assertOk()
        ->assertViewIs('pages.blog.show');
});

it('shows blog post content with table of contents', function () {
    $this->get(route('blog.show', 'laravel-paketleri'))
        ->assertSee("Laravel'de gerçekten lazım", escape: false)
        ->assertSee('İçindekiler', escape: false);
});
