<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        return Product::hydrate(DB::select('select * from products where is_active = true'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Product
    {
        abort_unless($product->is_active, 404);

        return $product;
    }
}
