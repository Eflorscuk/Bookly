<?php

use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->tenantA = Tenant::factory()->create();
    $this->tenantB = Tenant::factory()->create();

    Service::factory()->count(2)->for($this->tenantA)->create();
    Service::factory()->count(3)->for($this->tenantB)->create();
});

it('only shows services of the logged user tenant', function () {
    $this->actingAs(User::factory()->for($this->tenantA)->create());

    expect(Service::count())->toBe(2)
        ->and(Service::pluck('tenant_id')->unique()->values()->all())->toBe([$this->tenantA->id]);
});

it('cannot find a service from another tenant by id', function () {
    $foreign = Service::withoutGlobalScope('tenant')->where('tenant_id', $this->tenantB->id)->firstOrFail();

    $this->actingAs(User::factory()->for($this->tenantA)->create());

    expect(Service::find($foreign->id))->toBeNull();
});

it('fills tenant_id automatically on create', function () {
    $this->actingAs(User::factory()->for($this->tenantA)->create());

    $service = Service::create(['name' => 'Haircut', 'duration_minutes' => 30, 'price_cents' => 4500]);

    expect($service->tenant_id)->toBe($this->tenantA->id);
});
