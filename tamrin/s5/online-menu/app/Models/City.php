<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCity
 */
class City extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo<Province,City>
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * @return HasMany<UserAddress>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }
}
