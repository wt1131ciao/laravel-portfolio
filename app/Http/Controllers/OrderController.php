<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.ticket')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        $order->load('items.ticket');
        return view('orders.show', compact('order'));
    }
}
