<?php

declare(strict_types=1);

it('renders the stack page', function () {
    $this->get(route('stack'))
        ->assertOk()
        ->assertViewIs('pages.stack');
});

it('shows stack hero content', function () {
    $this->get(route('stack'))
        ->assertSee('Stack', escape: false)
        ->assertSee('Kullandığım teknolojiler', escape: false);
});
