<?php

declare(strict_types=1);

use App\Livewire\NewsletterForm;
use App\Models\Category;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('is rendered on the blog post page', function () {
    $post = Post::factory()->published()->for(Category::factory())->create();

    $this->get(route('blog.show', $post))
        ->assertSeeLivewire(NewsletterForm::class);
});

it('subscribes without a page reload', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', 'reader@example.com')
        ->call('subscribe')
        ->assertHasNoErrors()
        ->assertSet('subscribed', true)
        ->assertSet('email', '')
        ->assertSee('Aboneliğin tamam')
        ->assertNoRedirect();

    $this->assertDatabaseHas('subscribers', [
        'email' => 'reader@example.com',
        'is_active' => true,
    ]);
});

it('shows Turkish validation errors for a missing or invalid email', function (string $email, string $rule) {
    Livewire::test(NewsletterForm::class)
        ->set('email', $email)
        ->call('subscribe')
        ->assertHasErrors(['email' => $rule])
        ->assertSet('subscribed', false);

    expect(Subscriber::count())->toBe(0);
})->with([
    'empty' => ['', 'required'],
    'malformed' => ['not-an-email', 'email'],
]);

it('reactivates an inactive subscriber instead of duplicating it', function () {
    $subscriber = Subscriber::factory()->create([
        'email' => 'back@example.com',
        'is_active' => false,
    ]);

    Livewire::test(NewsletterForm::class)
        ->set('email', 'back@example.com')
        ->call('subscribe')
        ->assertSet('subscribed', true);

    expect(Subscriber::count())->toBe(1)
        ->and($subscriber->fresh()->is_active)->toBeTrue();
});

it('treats an already active subscriber as a success', function () {
    Subscriber::factory()->create(['email' => 'fan@example.com', 'is_active' => true]);

    Livewire::test(NewsletterForm::class)
        ->set('email', 'fan@example.com')
        ->call('subscribe')
        ->assertHasNoErrors()
        ->assertSet('subscribed', true);

    expect(Subscriber::count())->toBe(1);
});

it('silently drops submissions that fill the honeypot', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', 'bot@example.com')
        ->set('website', 'https://spam.example')
        ->call('subscribe')
        ->assertSet('subscribed', true);

    expect(Subscriber::count())->toBe(0);
});

it('rate limits repeated subscriptions from the same ip', function () {
    RateLimiter::clear('newsletter-form:127.0.0.1');

    foreach (range(1, 5) as $attempt) {
        Livewire::test(NewsletterForm::class)
            ->set('email', "reader{$attempt}@example.com")
            ->call('subscribe')
            ->assertSet('subscribed', true);
    }

    Livewire::test(NewsletterForm::class)
        ->set('email', 'reader6@example.com')
        ->call('subscribe')
        ->assertHasErrors('form')
        ->assertSet('subscribed', false);

    expect(Subscriber::where('email', 'reader6@example.com')->exists())->toBeFalse();
});
