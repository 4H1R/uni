<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class);
    }

    public function index(): Collection
    {
        return Order::hydrate(DB::select('select * from orders where user_id = :user_id',['user_id' => Auth::id()]));
    }

    public function show(Order $order): Order
    {
        return $order;
    }
}
