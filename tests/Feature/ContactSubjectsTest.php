<?php

declare(strict_types=1);

use App\Livewire\ContactForm;
use App\Models\Contact;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
});

/**
 * @param  array<string, mixed>  $overrides
 */
function fillContactForm(array $overrides = []): Testable
{
    $component = Livewire::test(ContactForm::class);

    foreach ([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'message' => 'Merhaba, konuşalım.',
        'kvkk_consent' => true,
        ...$overrides,
    ] as $field => $value) {
        $component->set($field, $value);
    }

    return $component;
}

it('offers the services as subjects, in order, followed by other', function () {
    Service::factory()->create(['title' => 'Danışmanlık', 'sort_order' => 2]);
    Service::factory()->create(['title' => 'Web Geliştirme', 'sort_order' => 1]);

    Livewire::test(ContactForm::class)
        ->assertSeeInOrder(['Web Geliştirme', 'Danışmanlık', 'Diğer']);
});

it('stores the service and a snapshot of its title as the subject', function () {
    $service = Service::factory()->create(['title' => 'Web Geliştirme']);

    fillContactForm(['subject' => (string) $service->id])
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('sent', true);

    $contact = Contact::sole();

    expect($contact->service_id)->toBe($service->id)
        ->and($contact->subject)->toBe('Web Geliştirme');
});

it('stores other as a subject without a service', function () {
    fillContactForm(['subject' => 'other'])->call('send')->assertHasNoErrors();

    $contact = Contact::sole();

    expect($contact->service_id)->toBeNull()
        ->and($contact->subject)->toBe('Diğer');
});

it('rejects unknown and deleted services as subjects', function () {
    $deleted = Service::factory()->create();
    $deleted->delete();

    fillContactForm(['subject' => '999999'])->call('send')->assertHasErrors(['subject']);
    fillContactForm(['subject' => (string) $deleted->id])->call('send')->assertHasErrors(['subject']);

    expect(Contact::count())->toBe(0);
});

it('soft deletes services so they disappear from the site but stay in the database', function () {
    $service = Service::factory()->create(['title' => 'Eski Hizmet']);

    $service->delete();

    expect(Service::count())->toBe(0)
        ->and(Service::withTrashed()->count())->toBe(1);

    $this->get(route('services'))->assertDontSee('Eski Hizmet');

    Livewire::test(ContactForm::class)->assertDontSee('Eski Hizmet');
});

it('keeps old messages intact when their service is deleted or renamed', function () {
    $service = Service::factory()->create(['title' => 'Web Geliştirme']);

    fillContactForm(['subject' => (string) $service->id])->call('send');

    $service->update(['title' => 'Yeni Ad']);
    $service->delete();

    $contact = Contact::sole();

    expect($contact->subject)->toBe('Web Geliştirme')
        ->and($contact->service)->not->toBeNull()
        ->and($contact->service->trashed())->toBeTrue();
});

it('preselects the subject from a mount parameter', function () {
    $service = Service::factory()->create();

    Livewire::test(ContactForm::class, ['service' => (string) $service->id])
        ->assertSet('subject', (string) $service->id);
});

it('ignores a preselected service that does not exist', function () {
    Livewire::test(ContactForm::class, ['service' => '999999'])
        ->assertSet('subject', '');
});

it('preselects the subject when a service is picked on the services page', function () {
    $service = Service::factory()->create();

    Livewire::test(ContactForm::class)
        ->dispatch('contact-subject-selected', subject: (string) $service->id)
        ->assertSet('subject', (string) $service->id)
        ->assertSet('sent', false);
});

it('opens the contact modal from each service card', function () {
    $service = Service::factory()->create(['title' => 'Web Geliştirme']);

    $this->get(route('services'))
        ->assertSeeLivewire(ContactForm::class)
        ->assertSee('id="contact-modal"', escape: false)
        ->assertSee('data-contact-service="'.$service->id.'"', escape: false)
        ->assertSee(route('contact', ['service' => $service->id]), escape: false);
});

it('preselects the service on the contact page from the query string', function () {
    $service = Service::factory()->create(['title' => 'Web Geliştirme']);

    $this->get(route('contact', ['service' => $service->id]))
        ->assertSee('<input type="hidden" name="subject" value="'.$service->id.'"', escape: false);
});

it('converts legacy enum subjects to their labels without losing messages', function () {
    DB::table('contacts')->insert([
        ['name' => 'A', 'email' => 'a@example.com', 'subject' => 'project_inquiry', 'message' => 'x', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'B', 'email' => 'b@example.com', 'subject' => 'other', 'message' => 'y', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'C', 'email' => 'c@example.com', 'subject' => 'Web Geliştirme', 'message' => 'z', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $migration = require collect(glob(database_path('migrations/*_convert_legacy_contact_subjects.php')))->sole();
    $migration->up();

    expect(DB::table('contacts')->orderBy('name')->pluck('subject')->all())
        ->toBe(['Proje Teklifi', 'Diğer', 'Web Geliştirme']);
});
