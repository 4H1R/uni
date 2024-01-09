<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\ProductRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function report(Request $request): array
    {
        $orderBy = match ($request->get('order')) {
            'total_sold' => 'total_items',
            default => 'products.id',
        };

        $base = DB::select('
            select
                SUM(products.quantity) AS products_quanitty_sum,
                AVG(products.price) AS avg_price,
                COUNT(CASE WHEN products.previous_price IS NULL THEN 1 END) AS products_without_discount_count,
                COUNT(CASE WHEN products.previous_price IS NOT NULL THEN 1 END) AS products_with_discount_count
            from products
        ');

        $query = "
        select products.*,sum(order_items.quantity) as total_sold
        from products
        join order_items on products.id = order_items.product_id
        group by products.id
        order by $orderBy desc
         ";

        return [
            'base' => $base,
            'products' => Product::hydrate(Db::select($query)),
        ];
    }

    public function reportProduct(Request $request, Product $product): array
    {
        $year = (int) ($request->get('year') ?? 50);

        $baseQuery = '
    SELECT
        SUM(order_items.quantity) AS total_sold_count,
        SUM(orders.total_amount) AS total_sold,
        COUNT(wish_lists.id) as wish_lists_count
        FROM products
        JOIN order_items ON products.id = order_items.product_id
        join orders on order_items.order_id = orders.id
        join wish_lists on products.id = wish_lists.product_id
        WHERE products.id = :product_id
        GROUP BY products.id
    ';

        $productChartQuery = '
        SELECT MONTH(orders.created_at) AS month, COUNT(orders.id) AS order_count
        FROM orders
        join order_items on orders.id = order_items.order_id
        WHERE order_items.product_id = :product_id
        AND orders.created_at >= DATE_SUB(NOW(), INTERVAL :year YEAR)
        GROUP BY month
        ORDER BY month
    ';

        $baseResult = Db::selectOne($baseQuery, ['product_id' => $product->id]);
        $productsChartResult = Db::select($productChartQuery, ['product_id' => $product->id, 'year' => $year]);

        $formattedProductsChart = array_fill_keys(range(1, 12), ['order_count' => 0]);
        foreach ($productsChartResult as $result) {
            $formattedProductsChart[$result->month] = [
                'order_count' => $result->order_count,
            ];
        }

        return [
            'base' => $baseResult,
            'products_chart' => $formattedProductsChart,
        ];
    }
}
