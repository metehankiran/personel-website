<?php

declare(strict_types=1);

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
