<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function index(): Collection
    {
        $query = 'select products.* from products
                join wish_lists on products.id = wish_lists.product_id
                where wish_lists.user_id = :user_id and is_active = 1
                ';

        return Product::hydrate(DB::select($query, ['user_id' => Auth::id()]));
    }

    public function store(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);
        $bindings = ['user_id' => Auth::id(), 'product_id' => $product->id];
        $exists = DB::selectOne('select * from wish_lists where user_id = :user_id and product_id = :product_id', $bindings);

        if (! $exists) {
            DB::insert('insert into wish_lists
            (product_id, user_id, created_at, updated_at)
            values(:product_id,:user_id,now(),now()) ', $bindings);
        }

        return new JsonResponse(['message' => 'Done'], 201);
    }

    public function destroy(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);
        $bindings = ['user_id' => Auth::id(), 'product_id' => $product->id];
        DB::delete('delete from wish_lists where product_id = :product_id and user_id = :user_id', $bindings);

        return new JsonResponse(['message' => 'Done'], 200);
    }
}
