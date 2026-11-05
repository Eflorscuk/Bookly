<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'price_cents',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'price_cents' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
