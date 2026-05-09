<?php

declare(strict_types=1);

it('renders a project detail page for any slug', function () {
    $this->get(route('projects.show', 'karavela'))
        ->assertOk()
        ->assertViewIs('pages.projects.show');
});

it('shows project detail content', function () {
    $this->get(route('projects.show', 'karavela'))
        ->assertSee('Karavela', escape: false)
        ->assertSee('Multi-tenant', escape: false);
});
