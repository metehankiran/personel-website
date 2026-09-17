<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Mail\ContactMessageReceived;
use App\Models\Contact;
use App\Models\Service;
use App\Settings\GeneralSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    /**
     * A service id, or self::OTHER_SUBJECT for messages that are not about a service.
     */
    public string $subject = '';

    public string $message = '';

    public bool $kvkk_consent = false;

    /**
     * Honeypot: hidden from humans, bots tend to fill it.
     */
    public string $website = '';

    public bool $sent = false;

    /**
     * Rendered inside the services page modal instead of the contact page card.
     */
    public bool $inModal = false;

    public const string OTHER_SUBJECT = 'other';

    private const string OTHER_SUBJECT_LABEL = 'Diğer';

    private const int MAX_ATTEMPTS = 3;

    private const int DECAY_SECONDS = 600;

    public function mount(?string $service = null, bool $inModal = false): void
    {
        $this->inModal = $inModal;

        $this->selectSubject((string) $service);
    }

    /**
     * Fired by the "Konuşalım" buttons on the services page.
     */
    #[On('contact-subject-selected')]
    public function selectSubject(string $subject): void
    {
        if (! in_array($subject, $this->subjectValues(), true)) {
            return;
        }

        $this->subject = $subject;
        $this->sent = false;

        $this->resetErrorBag('subject');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', Rule::in($this->subjectValues())],
            'message' => ['required', 'string', 'max:5000'],
            'kvkk_consent' => [$this->requiresKvkkConsent() ? 'accepted' : 'nullable'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'name' => 'Ad',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'subject' => 'Konu',
            'message' => 'Mesaj',
            'kvkk_consent' => 'KVKK onayı',
        ];
    }

    public function send(): void
    {
        $this->resetErrorBag('form');

        if (RateLimiter::tooManyAttempts($this->rateLimitKey(), self::MAX_ATTEMPTS)) {
            $this->addError('form', 'Çok fazla deneme yaptın. Lütfen birkaç dakika sonra tekrar dene.');

            return;
        }

        $validated = $this->validate();

        if ($this->website !== '') {
            $this->finish();

            return;
        }

        unset($validated['kvkk_consent']);

        $service = $this->services()->firstWhere('id', (int) $validated['subject']);

        $contact = Contact::create([
            ...$validated,
            'service_id' => $service?->id,
            'subject' => $service->title ?? self::OTHER_SUBJECT_LABEL,
            'phone' => $validated['phone'] !== '' ? $validated['phone'] : null,
        ]);

        RateLimiter::hit($this->rateLimitKey(), self::DECAY_SECONDS);

        $ownerEmail = app(GeneralSettings::class)->author_email;

        if (filled($ownerEmail)) {
            Mail::to($ownerEmail)->send(new ContactMessageReceived($contact));
        }

        $this->finish();
    }

    private function finish(): void
    {
        $this->reset(['name', 'email', 'phone', 'subject', 'message', 'kvkk_consent', 'website']);

        $this->sent = true;
    }

    private function rateLimitKey(): string
    {
        return 'contact-form:'.request()->ip();
    }

    public function startOver(): void
    {
        $this->sent = false;
    }

    /**
     * @return Collection<int, Service>
     */
    private function services(): Collection
    {
        return once(fn (): Collection => Service::ordered()->get(['id', 'title']));
    }

    /**
     * @return array<int, string>
     */
    private function subjectValues(): array
    {
        return [...$this->services()->map(fn (Service $service): string => (string) $service->id), self::OTHER_SUBJECT];
    }

    public function requiresKvkkConsent(): bool
    {
        return filled(app(GeneralSettings::class)->kvkk_page_slug);
    }

    public function render(): View
    {
        return view('livewire.contact-form', [
            // Union, not spread: spreading would renumber the service ids.
            'subjects' => $this->services()->pluck('title', 'id')->all() + [self::OTHER_SUBJECT => self::OTHER_SUBJECT_LABEL],
            'kvkkPageSlug' => app(GeneralSettings::class)->kvkk_page_slug,
        ]);
    }
}
