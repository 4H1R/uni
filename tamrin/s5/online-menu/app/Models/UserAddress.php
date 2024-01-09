<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperUserAddress
 */
class UserAddress extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo<User,UserAddress>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<City,UserAddress>
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
