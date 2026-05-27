<?php

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('visitor can subscribe to newsletter', function () {
    $this->post(route('newsletter.subscribe'), ['email' => 'test@example.com'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('subscribers', [
        'email' => 'test@example.com',
        'is_active' => true,
    ]);
});

test('subscribe requires valid email', function () {
    $this->post(route('newsletter.subscribe'), ['email' => 'invalid'])
        ->assertSessionHasErrors('email');
});

test('duplicate email does not create new record', function () {
    Subscriber::factory()->create(['email' => 'test@example.com']);

    $this->post(route('newsletter.subscribe'), ['email' => 'test@example.com'])
        ->assertRedirect();

    expect(Subscriber::where('email', 'test@example.com')->count())->toBe(1);
});

test('subscriber can unsubscribe via signed url', function () {
    $subscriber = Subscriber::factory()->create(['email' => 'test@example.com', 'is_active' => true]);

    $this->get(route('newsletter.unsubscribe', ['email' => $subscriber->email, 'token' => $subscriber->token]))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($subscriber->fresh()->is_active)->toBeFalse();
});

test('unsubscribe with invalid token fails', function () {
    Subscriber::factory()->create(['email' => 'test@example.com']);

    $this->get(route('newsletter.unsubscribe', ['email' => 'test@example.com', 'token' => 'wrong']))
        ->assertRedirect()
        ->assertSessionHas('error');
});
