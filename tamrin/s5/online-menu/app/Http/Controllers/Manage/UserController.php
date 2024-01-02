<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(): Collection
    {
        return User::hydrate(DB::select('select * from users'));
    }

    public function report(Request $request): Collection
    {
        $orderBy = match ($request->get('order')) {
            'total_orders' => 'total_orders',
            'total_spent' => 'total_spent',
            'total_addresses' => 'total_addresses',
            default => 'users.id',
        };

        $query = "
        select users.*,sum(orders.id) as total_orders ,sum(orders.total_amount) as total_spent,count(user_addresses.id) as total_addresses
        from users
        join orders on users.id = orders.user_id
        join user_addresses on users.id = user_addresses.user_id
        group by users.id
        order by $orderBy desc
         ";

        return User::hydrate(Db::select($query));
    }

    public function reportUser(Request $request, User $user): array
    {
        $year = (int) ($request->get('year') ?? 50);

        $baseQuery = '
        SELECT SUM(orders.id) AS total_orders, SUM(orders.total_amount) AS total_spent, COUNT(user_addresses.id) AS total_addresses
        FROM users
        JOIN orders ON users.id = orders.user_id
        JOIN user_addresses ON users.id = user_addresses.user_id
        WHERE users.id = :user_id
        GROUP BY users.id
    ';

        $topBoughtProducts = '
        SELECT products.* , sum(products.quantity) as products_count
        FROM products
        join order_items on products.id = order_items.product_id
        join orders on order_items.order_id = orders.id
        WHERE orders.user_id = :user_id
        group by products.id
        order by products_count desc
        limit 10
    ';

        $ordersChartQuery = '
        SELECT MONTH(orders.created_at) AS month, COUNT(orders.id) AS order_count,SUM(orders.total_amount) as total_spent,SUM(order_items.quantity) as products_count
        FROM orders
        join order_items on orders.id = order_items.order_id
        WHERE orders.user_id = :user_id
        AND orders.created_at >= DATE_SUB(NOW(), INTERVAL :year YEAR)
        GROUP BY month
        ORDER BY month
    ';

        $baseResult = Db::selectOne($baseQuery, ['user_id' => $user->id]);
        $ordersChartResult = Db::select($ordersChartQuery, ['user_id' => $user->id, 'year' => $year]);
        $topBoughtProductsResult = Db::select($topBoughtProducts, ['user_id' => $user->id]);

        $formattedOrdersChart = array_fill_keys(range(1, 12), ['order_count' => 0, 'total_spent' => 0, 'products_count' => 0]);
        foreach ($ordersChartResult as $result) {
            $formattedOrdersChart[$result->month] = [
                'order_count' => $result->order_count,
                'total_spent' => intval($result->total_spent),
                'products_count' => intval($result->products_count),
            ];
        }

        return [
            'base' => $baseResult,
            'orders_chart' => $formattedOrdersChart,
            'top_bought_products' => $topBoughtProductsResult,
        ];
    }

    public function show(User $user): User
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
