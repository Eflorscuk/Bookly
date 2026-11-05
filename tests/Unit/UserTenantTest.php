<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('belongs to a tenant', function () {
    $user = User::factory()->create();

    expect($user->tenant)->toBeInstanceOf(Tenant::class);
});

it('lists the users of a tenant', function () {
    $tenant = Tenant::factory()->create();
    User::factory()->count(2)->for($tenant)->create();
    User::factory()->create();

    expect($tenant->users)->toHaveCount(2);
});

it('cannot create a user without a tenant', function () {
    User::factory()->create(['tenant_id' => null]);
})->throws(QueryException::class);
