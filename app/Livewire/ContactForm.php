<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\ContactSubject;
use App\Mail\ContactMessageReceived;
use App\Models\Contact;
use App\Settings\GeneralSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $subject = '';

    public string $message = '';

    public bool $kvkk_consent = false;

    /**
     * Honeypot: hidden from humans, bots tend to fill it.
     */
    public string $website = '';

    public bool $sent = false;

    private const int MAX_ATTEMPTS = 3;

    private const int DECAY_SECONDS = 600;

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', Rule::enum(ContactSubject::class)],
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

        $contact = Contact::create([
            ...$validated,
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

    public function requiresKvkkConsent(): bool
    {
        return filled(app(GeneralSettings::class)->kvkk_page_slug);
    }

    public function render(): View
    {
        return view('livewire.contact-form', [
            'subjects' => ContactSubject::cases(),
            'kvkkPageSlug' => app(GeneralSettings::class)->kvkk_page_slug,
        ]);
    }
}
