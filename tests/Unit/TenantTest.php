<?php

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a tenant with a unique slug', function () {
    $tenant = Tenant::factory()->create();

    expect($tenant->slug)->not->toBeEmpty()
        ->and($tenant->fresh()->is_active)->toBeTrue();
});

it('soft deletes a tenant', function () {
    $tenant = Tenant::factory()->create();
    $tenant->delete();

    expect(Tenant::count())->toBe(0)
        ->and(Tenant::withTrashed()->count())->toBe(1);
});
