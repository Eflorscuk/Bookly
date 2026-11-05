<?php

use App\Models\Customer;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\QueryException;

beforeEach(function () {
    $this->tenantA = Tenant::factory()->create();
    $this->tenantB = Tenant::factory()->create();

    Customer::factory()->count(2)->for($this->tenantA)->create();
    Customer::factory()->count(3)->for($this->tenantB)->create();

    $this->actingAs(User::factory()->for($this->tenantA)->create());
});

it('only lists customers from the logged-in tenant', function () {
    expect(Customer::count())->toBe(2)
        ->and(Customer::pluck('tenant_id')->unique()->values()->all())
        ->toBe([$this->tenantA->id]);
});

it('fills tenant_id automatically on create', function () {
    $customer = Customer::create(['name' => 'Ana']);

    expect($customer->tenant_id)->toBe($this->tenantA->id);
});

it('allows the same email in different tenants', function () {
    Customer::factory()->for($this->tenantA)->create(['email' => 'ana@example.com']);
    Customer::factory()->for($this->tenantB)->create(['email' => 'ana@example.com']);

    expect(Customer::withoutGlobalScope('tenant')->where('email', 'ana@example.com')->count())
        ->toBe(2);
});

it('rejects a duplicate email in the same tenant', function () {
    Customer::factory()->for($this->tenantA)->create(['email' => 'ana@example.com']);
    Customer::factory()->for($this->tenantA)->create(['email' => 'ana@example.com']);
})->throws(QueryException::class);
