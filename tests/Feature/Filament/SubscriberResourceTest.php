<?php

use App\Filament\Resources\Subscribers\Pages\CreateSubscriber;
use App\Filament\Resources\Subscribers\Pages\EditSubscriber;
use App\Filament\Resources\Subscribers\Pages\ListSubscribers;
use App\Models\Subscriber;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListSubscribers::class)->assertOk();
});

test('subscribers appear in the table', function () {
    $subscribers = Subscriber::factory()->count(3)->create();

    Livewire::test(ListSubscribers::class)
        ->assertCanSeeTableRecords($subscribers);
});

test('subscriber can be created (token auto-generated)', function () {
    Livewire::test(CreateSubscriber::class)
        ->fillForm([
            'email' => 'newsletter@example.com',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $subscriber = Subscriber::firstWhere('email', 'newsletter@example.com');
    expect($subscriber)->not->toBeNull();
    expect($subscriber->token)->toHaveLength(64);
});

test('email is required', function () {
    Livewire::test(CreateSubscriber::class)
        ->fillForm(['email' => null])
        ->call('create')
        ->assertHasFormErrors(['email' => 'required']);
});

test('email must be unique', function () {
    Subscriber::factory()->create(['email' => 'taken@example.com']);

    Livewire::test(CreateSubscriber::class)
        ->fillForm(['email' => 'taken@example.com'])
        ->call('create')
        ->assertHasFormErrors(['email']);
});

test('subscriber can be deactivated', function () {
    $subscriber = Subscriber::factory()->create(['is_active' => true]);

    Livewire::test(EditSubscriber::class, ['record' => $subscriber->getRouteKey()])
        ->fillForm(['is_active' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($subscriber->refresh()->is_active)->toBeFalse();
});

test('subscriber can be deleted from edit page', function () {
    $subscriber = Subscriber::factory()->create();

    Livewire::test(EditSubscriber::class, ['record' => $subscriber->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('subscribers', ['id' => $subscriber->id]);
});
