<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\ProductRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return Product::hydrate(DB::select('select * from products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): Product
    {
        DB::insert('insert into products
        (name, price, previous_price, short_description, description,quantity,is_active, created_at, updated_at)
        values (:name,:price,:previous_price,:short_description,:description,:quantity,:is_active,now(),now())', $request->validated());

        return Product::hydrate(DB::select('select * from products where id = ?', [DB::getPdo()->lastInsertId()]))->first();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Product
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): Product
    {
        DB::update('update products set
        name = :name,
        price = :price,
        previous_price = :previous_price,
        short_description = :short_description,
        description = :description,
        quantity = :quantity,
        is_active = :is_active,
        updated_at = now()
        where id = :id',
            [...$request->validated(), 'id' => $product->id]);

        return Product::hydrate(DB::select('select * from products where id = ?', [$product->id]))->first();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        DB::delete('delete from products where id = ?', [$product->id]);

        return response()->json(['message' => "You've deleted the product successfully."]);
    }
}
