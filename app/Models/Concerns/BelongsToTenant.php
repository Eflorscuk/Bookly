<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if ($tenantId = auth()->user()?->tenant_id) {
                $query->where($query->qualifyColumn('tenant_id'), $tenantId);
            }
        });

        static::creating(function (self $model) {
            if (! $model->tenant_id && $tenantId = auth()->user()?->tenant_id) {
                $model->tenant_id = $tenantId;
            }
        });
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
