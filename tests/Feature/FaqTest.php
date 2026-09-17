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
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', test()->get(route('services'))->getContent(), $matches);

    return collect($matches[1])
        ->map(fn (string $json): array => json_decode($json, true, flags: JSON_THROW_ON_ERROR))
        ->flatMap(fn (array $data): array => $data['@graph'] ?? [$data])
        ->firstWhere('@type', 'FAQPage');
}

it('shows the questions on the services page in order, with the answers in the html', function () {
    Faq::factory()->create(['question' => 'Teslim süresi ne kadar?', 'answer' => 'Kapsama göre 4 ila 12 hafta.', 'sort_order' => 2]);
    Faq::factory()->create(['question' => 'Nasıl fiyatlandırıyorsun?', 'answer' => "Proje bazlı sabit fiyat.\nRetainer de mümkün.", 'sort_order' => 1]);

    $this->get(route('services'))
        ->assertOk()
        ->assertSeeInOrder(['Sık sorulan sorular', 'Nasıl fiyatlandırıyorsun?', 'Proje bazlı sabit fiyat.', 'Teslim süresi ne kadar?', 'Kapsama göre 4 ila 12 hafta.'])
        ->assertSee('<details', escape: false)
        ->assertSee("Proje bazlı sabit fiyat.<br />\nRetainer de mümkün.", escape: false);
});

it('uses a question heading for each entry', function () {
    Faq::factory()->create(['question' => 'Teslim süresi ne kadar?']);

    expect($this->get(route('services'))->getContent())->toMatch('#<h3[^>]*>\s*Teslim süresi ne kadar\?\s*</h3>#u');
});

it('hides the section and the schema when there are no questions', function () {
    $this->get(route('services'))->assertOk()->assertDontSee('Sık sorulan sorular');

    expect(faqSchema())->toBeNull();
});

it('hides unpublished questions from the page and the schema', function () {
    Faq::factory()->create(['question' => 'Yayında mı?', 'is_published' => true]);
    Faq::factory()->create(['question' => 'Gizli soru?', 'is_published' => false]);

    $this->get(route('services'))->assertSee('Yayında mı?')->assertDontSee('Gizli soru?');

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

    $this->get(route('services'))->assertDontSee('<script>alert(1)</script>', escape: false)->assertDontSee('<b>kalın</b>', escape: false);
});

describe('in the panel', function () {
    beforeEach(fn () => $this->actingAs(User::factory()->create()));

    it('is labelled in Turkish', function () {
        expect(FaqResource::getModelLabel())->toBe('Soru')
            ->and(FaqResource::getPluralModelLabel())->toBe('Sık Sorulan Sorular')
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
