<?php

declare(strict_types=1);

use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function faqSchema(): ?array
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', test()->get(route('faq'))->getContent(), $matches);

    return collect($matches[1])
        ->map(fn (string $json): array => json_decode($json, true, flags: JSON_THROW_ON_ERROR))
        ->flatMap(fn (array $data): array => $data['@graph'] ?? [$data])
        ->firstWhere('@type', 'FAQPage');
}

it('shows the questions on their own page in order, with the answers in the html', function () {
    Faq::factory()->create(['question' => 'Teslim süresi ne kadar?', 'answer' => 'Kapsama göre 4 ila 12 hafta.', 'sort_order' => 2]);
    Faq::factory()->create(['question' => 'Nasıl fiyatlandırıyorsun?', 'answer' => "Proje bazlı sabit fiyat.\nRetainer de mümkün.", 'sort_order' => 1]);

    $this->get(route('faq'))
        ->assertOk()
        ->assertSeeInOrder(['Sıkça sorulan sorular', 'Nasıl fiyatlandırıyorsun?', 'Proje bazlı sabit fiyat.', 'Teslim süresi ne kadar?', 'Kapsama göre 4 ila 12 hafta.'])
        ->assertSee('<details', escape: false)
        ->assertSee("Proje bazlı sabit fiyat.<br />\nRetainer de mümkün.", escape: false);
});

it('uses a question heading for each entry', function () {
    Faq::factory()->create(['question' => 'Teslim süresi ne kadar?']);

    expect($this->get(route('faq'))->getContent())->toMatch('#<h3[^>]*>\s*Teslim süresi ne kadar\?\s*</h3>#u');
});

it('shows an empty state and no schema when there are no questions', function () {
    $this->get(route('faq'))->assertOk()->assertSee('Henüz bir soru eklenmedi');

    expect(faqSchema())->toBeNull();
});

it('hides unpublished questions from the page and the schema', function () {
    Faq::factory()->create(['question' => 'Yayında mı?', 'is_published' => true]);
    Faq::factory()->create(['question' => 'Gizli soru?', 'is_published' => false]);

    $this->get(route('faq'))->assertSee('Yayında mı?')->assertDontSee('Gizli soru?');

    expect(array_column(faqSchema()['mainEntity'], 'name'))->toBe(['Yayında mı?']);
});

it('publishes FAQPage structured data with plain text answers', function () {
    Faq::factory()->create(['question' => 'Nasıl fiyatlandırıyorsun?', 'answer' => "Proje bazlı sabit fiyat.\nRetainer de mümkün.", 'sort_order' => 1]);

    expect(faqSchema()['mainEntity'])->toBe([[
        '@type' => 'Question',
        'name' => 'Nasıl fiyatlandırıyorsun?',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Proje bazlı sabit fiyat. Retainer de mümkün.'],
    ]]);
});

it('escapes html in questions and answers', function () {
    Faq::factory()->create(['question' => 'Soru <script>alert(1)</script>?', 'answer' => '<b>kalın</b>']);

    $this->get(route('faq'))->assertDontSee('<script>alert(1)</script>', escape: false)->assertDontSee('<b>kalın</b>', escape: false);
});

describe('in the panel', function () {
    beforeEach(fn () => $this->actingAs(User::factory()->create()));

    it('is labelled in Turkish', function () {
        expect(FaqResource::getModelLabel())->toBe('Soru')
            ->and(FaqResource::getPluralModelLabel())->toBe('Sıkça Sorulan Sorular')
            ->and(FaqResource::getNavigationGroup())->toBe('Hakkımda');
    });

    it('lists, creates, edits and reorders questions', function () {
        $existing = Faq::factory()->create(['sort_order' => 1]);

        Livewire::test(ListFaqs::class)->assertOk()->assertCanSeeTableRecords([$existing]);

        Livewire::test(CreateFaq::class)
            ->fillForm(['question' => 'İade var mı?', 'answer' => 'İlk hafta koşulsuz.'])
            ->call('create')
            ->assertHasNoFormErrors();

        $created = Faq::firstWhere('question', 'İade var mı?');

        expect($created->sort_order)->toBe(2)->and($created->is_published)->toBeTrue();

        Livewire::test(EditFaq::class, ['record' => $created->getRouteKey()])
            ->fillForm(['answer' => 'İlk iki hafta koşulsuz.'])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($created->fresh()->answer)->toBe('İlk iki hafta koşulsuz.');

        Livewire::test(ListFaqs::class)->call('reorderTable', [$created->getKey(), $existing->getKey()]);

        expect(Faq::ordered()->pluck('id')->all())->toBe([$created->id, $existing->id]);
    });

    it('requires a question and an answer', function () {
        Livewire::test(CreateFaq::class)
            ->fillForm(['question' => '', 'answer' => ''])
            ->call('create')
            ->assertHasFormErrors(['question' => 'required', 'answer' => 'required']);
    });
});

it('lives at a Turkish url with its own title, description and breadcrumb', function () {
    Faq::factory()->create(['question' => 'Teslim süresi ne kadar?']);

    expect(route('faq', absolute: false))->toBe('/sss');

    $html = $this->get('/sss')->assertOk()->getContent();

    expect($html)->toContain('<title>Sıkça Sorulan Sorular — ')
        ->toContain('"@type":"BreadcrumbList"')
        ->toMatch('/<meta name="description" content="[^"]*Teslim süresi ne kadar\?/u');
});

it('is linked from the "Sayfalar" menu on desktop and mobile, even before any static page exists', function () {
    $header = Str::between($this->get(route('home'))->getContent(), '<header', '</header>');

    expect(substr_count($header, 'href="'.route('faq').'"'))->toBe(2)
        ->and($header)->toContain('Sıkça Sorulan Sorular')
        ->and(substr_count($header, 'data-nav-group="pages"'))->toBe(2);
});

it('marks the menu and the link as current on the faq page', function () {
    $header = Str::between($this->get(route('faq'))->getContent(), '<header', '</header>');

    expect(substr_count($header, 'data-nav-group="pages" data-active="true"'))->toBe(2)
        ->and(substr_count($header, 'href="'.route('faq').'" aria-current="page"'))->toBe(2);
});

it('no longer repeats the questions on the services page, but points to them', function () {
    Faq::factory()->create(['question' => 'Teslim süresi ne kadar?']);

    $html = $this->get(route('services'))->assertOk()->getContent();

    expect($html)->not->toContain('Teslim süresi ne kadar?')
        ->not->toContain('"@type":"FAQPage"')
        ->toContain('href="'.route('faq').'"');
});

it('does not point to an empty faq from the services page', function () {
    expect(Str::between($this->get(route('services'))->getContent(), '<main', '</main>'))->not->toContain('href="'.route('faq').'"');
});

it('lists the faq page in the sitemap, llms.txt and the site search only when it has questions', function () {
    config(['app.url' => 'https://example.test']);
    URL::forceRootUrl('https://example.test');

    expect($this->get('/sitemap.xml')->getContent())->not->toContain('/sss')
        ->and($this->get('/llms.txt')->getContent())->not->toContain('/sss');

    Faq::factory()->create();

    expect($this->get('/sitemap.xml')->getContent())->toContain('<loc>https://example.test/sss</loc>')
        ->and($this->get('/llms.txt')->getContent())->toContain('(https://example.test/sss)')
        ->and(collect($this->getJson(route('search.index'))->json())->pluck('title'))->toContain('Sıkça Sorulan Sorular');
});

it('is linked from the footer with a label short enough for the narrow mobile columns', function () {
    $footer = Str::after($this->get(route('home'))->getContent(), 'role="contentinfo"');

    expect($footer)->toMatch('#<a href="'.preg_quote(route('faq'), '#').'"[^>]*>\s*SSS\s*</a>#u');
});
