<?php

declare(strict_types=1);

it('renders an accessible five star scale', function () {
    $html = (string) $this->blade('<x-rating-stars :rating="4" />');

    expect($html)->toContain('role="img"')
        ->toContain('aria-label="5 üzerinden 4"')
        ->and(substr_count($html, 'data-star="filled"'))->toBe(4)
        ->and(substr_count($html, 'data-star="empty"'))->toBe(1);
});

it('renders nothing without a rating', function () {
    expect(trim((string) $this->blade('<x-rating-stars :rating="null" />')))->toBe('');
});
