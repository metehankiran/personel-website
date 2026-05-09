<?php

declare(strict_types=1);

it('renders the bookmarks page', function () {
    $this->get(route('bookmarks'))
        ->assertOk()
        ->assertViewIs('pages.bookmarks');
});

it('shows the bookmarks hero content', function () {
    $this->get(route('bookmarks'))
        ->assertSee('Yer İşaretlerim', escape: false)
        ->assertSee('Faydalı linkler', escape: false);
});

it('groups links into categorized sections', function () {
    $this->get(route('bookmarks'))
        ->assertSee('Geliştirme', escape: false)
        ->assertSee('Tasarım', escape: false)
        ->assertSee('Okuma', escape: false);
});
