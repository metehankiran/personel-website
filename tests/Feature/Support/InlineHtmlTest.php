<?php

declare(strict_types=1);

use App\Filament\Pages\ManageGeneralSettings;
use App\Models\User;
use App\Settings\GeneralSettings;
use App\Support\InlineHtml;
use Filament\Forms\Components\RichEditor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('unwraps a single rich editor paragraph', function () {
    expect(InlineHtml::from('<p>Bağımsız <em>full-stack</em> developer.</p>'))
        ->toBe('Bağımsız <em>full-stack</em> developer.');
});

it('joins multiple paragraphs with line breaks', function () {
    expect(InlineHtml::from('<p>Bağımsız</p><p><strong>developer.</strong></p>'))
        ->toBe('Bağımsız<br><strong>developer.</strong>');
});

it('leaves plain inline html untouched', function () {
    expect(InlineHtml::from('Bağımsız <em>full-stack</em> developer.'))
        ->toBe('Bağımsız <em>full-stack</em> developer.');
});

it('strips block elements, scripts and attributes', function () {
    expect(InlineHtml::from('<h2>Merhaba</h2><p><em onclick="alert(1)" class="x">dünya</em><script>alert(1)</script></p>'))
        ->toBe('Merhaba<br><em>dünya</em>alert(1)');
});

it('returns an empty string for blank or empty editor content', function (?string $html) {
    expect(InlineHtml::from($html))->toBe('');
})->with([null, '', '<p></p>', '<p><br></p>']);

it('edits the hero title with an inline-only rich editor', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ManageGeneralSettings::class)
        ->assertFormFieldExists('hero_title', fn ($component): bool => $component instanceof RichEditor
            && $component->getToolbarButtons() === [['bold', 'italic', 'underline'], ['undo', 'redo']]);
});

it('renders a rich editor hero title as inline html inside the heading', function () {
    $settings = app(GeneralSettings::class);
    $settings->hero_title = '<p>Bağımsız <em>full-stack</em> developer.</p>';
    $settings->save();

    $content = $this->get(route('home'))->getContent();

    expect($content)->toContain('Bağımsız <em>full-stack</em> developer.</h1>')
        ->and($content)->not->toContain('<p>Bağımsız');
});
