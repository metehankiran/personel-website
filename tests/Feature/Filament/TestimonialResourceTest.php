<?php

use App\Filament\Resources\Testimonials\Pages\CreateTestimonial;
use App\Filament\Resources\Testimonials\Pages\EditTestimonial;
use App\Filament\Resources\Testimonials\Pages\ListTestimonials;
use App\Models\Testimonial;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListTestimonials::class)->assertOk();
});

test('testimonials appear in the table', function () {
    $testimonials = Testimonial::factory()->count(3)->create();

    Livewire::test(ListTestimonials::class)
        ->assertCanSeeTableRecords($testimonials);
});

test('testimonial can be created without avatar', function () {
    Livewire::test(CreateTestimonial::class)
        ->fillForm([
            'name' => 'Ayşe Yıldız',
            'title' => 'Product Manager',
            'company' => 'Acme',
            'body' => 'Harika bir iş çıkardı, kesinlikle tavsiye ederim.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('testimonials', [
        'name' => 'Ayşe Yıldız',
        'title' => 'Product Manager',
        'company' => 'Acme',
    ]);
});

test('testimonial can be created with avatar', function () {
    Storage::fake('public');

    Livewire::test(CreateTestimonial::class)
        ->fillForm([
            'name' => 'Mehmet Demir',
            'title' => 'CTO',
            'body' => 'Profesyonel ve zamanında teslim eden bir ekip.',
            'avatar' => UploadedFile::fake()->image('avatar.png', 400, 400),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $testimonial = Testimonial::firstWhere('name', 'Mehmet Demir');

    expect($testimonial)->not->toBeNull();
    expect($testimonial->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($testimonial->avatar);
});

test('testimonial can be created with a rating', function () {
    Livewire::test(CreateTestimonial::class)
        ->fillForm([
            'name' => 'Ayşe Yıldız',
            'title' => 'Product Manager',
            'body' => 'Harika bir iş çıkardı.',
            'rating' => 5,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Testimonial::firstWhere('name', 'Ayşe Yıldız')->rating)->toBe(5);
});

test('rating must be between one and five', function () {
    Livewire::test(CreateTestimonial::class)
        ->fillForm(['name' => 'Ayşe', 'title' => 'PM', 'body' => 'Yorum.', 'rating' => 6])
        ->call('create')
        ->assertHasFormErrors(['rating']);
});

test('name is required', function () {
    Livewire::test(CreateTestimonial::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('title is required', function () {
    Livewire::test(CreateTestimonial::class)
        ->fillForm(['title' => null])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

test('body is required', function () {
    Livewire::test(CreateTestimonial::class)
        ->fillForm(['body' => null])
        ->call('create')
        ->assertHasFormErrors(['body' => 'required']);
});

test('testimonial can be edited', function () {
    $testimonial = Testimonial::factory()->create(['name' => 'Old Name']);

    Livewire::test(EditTestimonial::class, ['record' => $testimonial->getRouteKey()])
        ->fillForm(['name' => 'New Name'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($testimonial->refresh()->name)->toBe('New Name');
});

test('testimonial can be deleted from edit page', function () {
    $testimonial = Testimonial::factory()->create();

    Livewire::test(EditTestimonial::class, ['record' => $testimonial->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Testimonial::factory()->create(['sort_order' => 1]);
    $b = Testimonial::factory()->create(['sort_order' => 2]);

    Livewire::test(ListTestimonials::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
