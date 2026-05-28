<?php

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Models\Brand;
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
    Livewire::test(ListBrands::class)->assertOk();
});

test('brands appear in the table', function () {
    $brands = Brand::factory()->count(3)->create();

    Livewire::test(ListBrands::class)
        ->assertCanSeeTableRecords($brands);
});

test('brand can be created without logo', function () {
    Livewire::test(CreateBrand::class)
        ->fillForm([
            'name' => 'Acme',
            'url' => 'https://acme.test',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('brands', [
        'name' => 'Acme',
        'url' => 'https://acme.test',
    ]);
});

test('brand can be created with logo', function () {
    Storage::fake('public');

    Livewire::test(CreateBrand::class)
        ->fillForm([
            'name' => 'Logoed',
            'logo' => UploadedFile::fake()->image('logo.png', 600, 200),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $brand = Brand::firstWhere('name', 'Logoed');

    expect($brand)->not->toBeNull();
    expect($brand->logo)->not->toBeNull();
    Storage::disk('public')->assertExists($brand->logo);
});

test('name is required', function () {
    Livewire::test(CreateBrand::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('brand can be edited', function () {
    $brand = Brand::factory()->create(['name' => 'Old']);

    Livewire::test(EditBrand::class, ['record' => $brand->getRouteKey()])
        ->fillForm(['name' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($brand->refresh()->name)->toBe('New');
});

test('brand can be deleted from edit page', function () {
    $brand = Brand::factory()->create();

    Livewire::test(EditBrand::class, ['record' => $brand->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Brand::factory()->create(['sort_order' => 1]);
    $b = Brand::factory()->create(['sort_order' => 2]);

    Livewire::test(ListBrands::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
