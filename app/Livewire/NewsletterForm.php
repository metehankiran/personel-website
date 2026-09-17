<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Subscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class NewsletterForm extends Component
{
    public string $email = '';

    /**
     * Honeypot: hidden from humans, bots tend to fill it.
     */
    public string $website = '';

    public bool $subscribed = false;

    private const int MAX_ATTEMPTS = 5;

    private const int DECAY_SECONDS = 600;

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'email' => 'E-posta',
        ];
    }

    public function subscribe(): void
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

        $subscriber = Subscriber::firstOrCreate(['email' => $validated['email']]);

        if (! $subscriber->is_active) {
            $subscriber->update(['is_active' => true]);
        }

        RateLimiter::hit($this->rateLimitKey(), self::DECAY_SECONDS);

        $this->finish();
    }

    private function finish(): void
    {
        $this->reset(['email', 'website']);

        $this->subscribed = true;
    }

    private function rateLimitKey(): string
    {
        return 'newsletter-form:'.request()->ip();
    }

    public function render(): View
    {
        return view('livewire.newsletter-form');
    }
}
