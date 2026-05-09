<?php

declare(strict_types=1);

it('renders the projects index page', function () {
    $this->get(route('projects'))
        ->assertOk()
        ->assertViewIs('pages.projects.index');
});

it('shows the projects hero content', function () {
    $this->get(route('projects'))
        ->assertSee('Projeler', escape: false)
        ->assertSee('Yaptığım işler', escape: false);
});
