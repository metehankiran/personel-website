<?php

use App\Enums\EducationDegree;
use App\Filament\Resources\Education\Pages\CreateEducation;
use App\Filament\Resources\Education\Pages\EditEducation;
use App\Filament\Resources\Education\Pages\ListEducation;
use App\Models\Education;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListEducation::class)->assertOk();
});

test('education entries appear in the table', function () {
    $entries = Education::factory()->count(3)->create();

    Livewire::test(ListEducation::class)
        ->assertCanSeeTableRecords($entries);
});

test('education can be created with degree enum', function () {
    Livewire::test(CreateEducation::class)
        ->fillForm([
            'school' => 'Boğaziçi University',
            'degree' => EducationDegree::Bachelor->value,
            'field' => 'Computer Science',
            'gpa' => '3.45/4',
            'start_date' => '2014-09-01',
            'end_date' => '2018-06-30',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $education = Education::firstWhere('school', 'Boğaziçi University');
    expect($education->degree)->toBe(EducationDegree::Bachelor);
});

test('school is required', function () {
    Livewire::test(CreateEducation::class)
        ->fillForm(['school' => null])
        ->call('create')
        ->assertHasFormErrors(['school' => 'required']);
});

test('degree is required', function () {
    Livewire::test(CreateEducation::class)
        ->fillForm(['degree' => null])
        ->call('create')
        ->assertHasFormErrors(['degree' => 'required']);
});

test('field is required', function () {
    Livewire::test(CreateEducation::class)
        ->fillForm(['field' => null])
        ->call('create')
        ->assertHasFormErrors(['field' => 'required']);
});

test('start_date is required', function () {
    Livewire::test(CreateEducation::class)
        ->fillForm(['start_date' => null])
        ->call('create')
        ->assertHasFormErrors(['start_date' => 'required']);
});

test('education can be edited', function () {
    $education = Education::factory()->create(['school' => 'Old School']);

    Livewire::test(EditEducation::class, ['record' => $education->getRouteKey()])
        ->fillForm(['school' => 'New School'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($education->refresh()->school)->toBe('New School');
});

test('education can be deleted from edit page', function () {
    $education = Education::factory()->create();

    Livewire::test(EditEducation::class, ['record' => $education->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('education', ['id' => $education->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Education::factory()->create(['sort_order' => 1]);
    $b = Education::factory()->create(['sort_order' => 2]);

    Livewire::test(ListEducation::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
