<?php

declare(strict_types=1);

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;

uses(RefreshDatabase::class);

function renderSelect(string $attributes = ''): string
{
    return Blade::render(
        '<x-select name="topic" id="topic" :options="$options" placeholder="Seçiniz" '.$attributes.' />',
        ['options' => ['1' => 'Web Geliştirme', 'other' => 'Diğer']],
    );
}

it('renders an accessible listbox instead of a native select', function () {
    $html = renderSelect();

    expect($html)->not->toContain('<select')
        ->and($html)->toContain('role="combobox"')
        ->and($html)->toContain('aria-haspopup="listbox"')
        ->and($html)->toContain('role="listbox"')
        ->and($html)->toContain('role="option"')
        ->and($html)->toContain('Web Geliştirme')
        ->and($html)->toContain('Diğer');
});

it('submits its value through a named hidden input', function () {
    expect(renderSelect('selected="other"'))
        ->toContain('<input type="hidden" name="topic" value="other"');
});

it('shows the placeholder until something is selected and the label afterwards', function () {
    expect(renderSelect())->toContain('Seçiniz');

    $html = renderSelect('selected="1"');

    expect($html)->toContain('aria-selected="true"')
        ->and(substr_count($html, 'aria-selected="true"'))->toBe(1);
});

it('marks itself invalid when asked to', function () {
    expect(renderSelect(':invalid="true"'))->toContain('aria-invalid="true"');
});

it('forwards wire:model so Livewire can bind to it', function () {
    expect(renderSelect('wire:model="subject"'))->toContain('wire:model="subject"');
});

it('is used for the subject on the contact page with the service preselected', function () {
    $service = Service::factory()->create(['title' => 'Web Geliştirme']);

    $this->get(route('contact', ['service' => $service->id]))
        ->assertDontSee('<select', escape: false)
        ->assertSee('role="combobox"', escape: false)
        ->assertSee('<input type="hidden" name="subject" value="'.$service->id.'"', escape: false);
});
