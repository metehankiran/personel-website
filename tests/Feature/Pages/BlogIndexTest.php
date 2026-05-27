<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the blog index page', function () {
    $this->get(route('blog'))
        ->assertOk()
        ->assertViewIs('pages.blog.index');
});

it('shows the blog hero content', function () {
    $this->get(route('blog'))
        ->assertSee('Yazılar', escape: false)
        ->assertSee('öğrendiklerimi', escape: false);
});
