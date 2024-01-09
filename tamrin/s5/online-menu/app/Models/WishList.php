<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperWishList
 */
class WishList extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo<Product,WishList>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<User,WishList>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
