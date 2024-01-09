<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @return HasMany<User>
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
