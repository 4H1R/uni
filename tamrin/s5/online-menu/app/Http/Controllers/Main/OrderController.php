<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): Collection
    {
        $query = 'select * from orders
        where user_id = :user_id
        order by orders.id desc';

        return Order::hydrate(DB::select($query, ['user_id' => Auth::id()]));
    }

    public function show(Order $order): Order
    {
        abort_unless(Auth::id() === $order->user_id, 403);

        return $order;
    }
}
