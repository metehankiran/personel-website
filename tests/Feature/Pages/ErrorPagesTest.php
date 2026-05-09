<?php

declare(strict_types=1);

it('serves a custom 404 page for unknown routes', function () {
    $this->get('/this-route-does-not-exist')
        ->assertNotFound()
        ->assertSee('404', escape: false)
        ->assertSee('Sayfa kayıp', escape: false);
});

it('renders the custom 500 error view', function () {
    expect(view('errors.500')->render())
        ->toContain('500')
        ->toContain('Sunucuda bir şey patladı');
});

it('renders the custom 503 maintenance view', function () {
    expect(view('errors.503')->render())
        ->toContain('Kısa bir bakım yapıyorum');
});
