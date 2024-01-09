<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperProvince
 */
class Province extends Model
{
    use HasFactory;

    /**
     * @return HasMany<City>
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
