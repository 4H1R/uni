<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class);
    }

    public function index(): Collection
    {
        $query = 'select * from orders
        order by orders.id desc';

        return Order::hydrate(DB::select($query));
    }

    public function report(Request $request): Collection
    {
        $orderBy = match ($request->get('order')) {
            'total_items' => 'total_items',
            default => 'orders.id',
        };

        $query = "
        select orders.*,sum(order_items.quantity) as total_items
        from orders
        join order_items on orders.id = order_items.order_id
        group by orders.id
        order by $orderBy desc
         ";

        return Order::hydrate(Db::select($query));
    }

    public function reportOrder(Request $request, Order $order): array
    {
        $baseQuery = '
    SELECT
        SUM(order_items.quantity) AS products_count,
        AVG(products.price) AS avg_price,
        COUNT(CASE WHEN products.previous_price IS NULL THEN 1 END) AS products_without_discount_count,
        COUNT(CASE WHEN products.previous_price IS NOT NULL THEN 1 END) AS products_with_discount_count
    FROM orders
    JOIN order_items ON orders.id = order_items.order_id
    JOIN products ON order_items.product_id = products.id
    WHERE orders.id = :order_id
    GROUP BY orders.id
';

        $productsQuery = '
        SELECT products.*
        FROM products
        join order_items on products.id = order_items.product_id
        join orders on order_items.order_id = orders.id
        WHERE orders.id = :order_id
    ';

        $baseResult = Db::selectOne($baseQuery, ['order_id' => $order->id]);
        $productsResult = Db::select($productsQuery, ['order_id' => $order->id]);

        return [
            'base' => $baseResult,
            'products' => $productsResult,
        ];
    }

    public function show(Order $order): Order
    {
        return $order;
    }
}
