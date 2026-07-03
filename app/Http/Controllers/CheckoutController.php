<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity'  => 'required|integer|min:1|max:10',
        ]);

        $ticket = Ticket::findOrFail($validated['ticket_id']);

        if (!$ticket->isAvailable()) {
            return back()->withErrors(['ticket' => 'このチケットは現在購入できません。']);
        }

        if ($ticket->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => '在庫が不足しています。（残り ' . $ticket->stock . ' 枚）']);
        }

        $order = DB::transaction(function () use ($ticket, $validated, $request) {
            $order = Order::create([
                'user_id'      => $request->user()->id,
                'total_amount' => $ticket->price * $validated['quantity'],
                'status'       => 'pending',
            ]);

            OrderItem::create([
                'order_id'   => $order->id,
                'ticket_id'  => $ticket->id,
                'quantity'   => $validated['quantity'],
                'unit_price' => $ticket->price,
            ]);

            return $order;
        });

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'jpy',
                    'product_data' => ['name' => $ticket->name],
                    'unit_amount'  => $ticket->price,
                ],
                'quantity' => $validated['quantity'],
            ]],
            'mode'        => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('checkout.cancel') . '?order_id=' . $order->id,
            'metadata'    => ['order_id' => $order->id],
        ]);

        $order->update(['stripe_checkout_session_id' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('tickets.index');
        }

        $order = Order::where('stripe_checkout_session_id', $sessionId)
            ->where('user_id', $request->user()->id)
            ->with('items.ticket')
            ->first();

        return view('checkout.success', compact('order'));
    }

    public function cancel(Request $request)
    {
        $orderId = $request->query('order_id');

        if ($orderId) {
            Order::where('id', $orderId)
                ->where('user_id', $request->user()->id)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled']);
        }

        return view('checkout.cancel');
    }
}
