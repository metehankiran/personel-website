<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\ContactSubject;
use App\Models\Contact;
use App\Settings\GeneralSettings;
use Illuminate\Contracts\View\View;
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

    public bool $sent = false;

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
        $validated = $this->validate();

        unset($validated['kvkk_consent']);

        Contact::create([
            ...$validated,
            'phone' => $validated['phone'] !== '' ? $validated['phone'] : null,
        ]);

        $this->reset(['name', 'email', 'phone', 'subject', 'message', 'kvkk_consent']);

        $this->sent = true;
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
