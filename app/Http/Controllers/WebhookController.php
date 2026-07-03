<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload       = $request->getContent();
        $sigHeader     = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $this->handleCheckoutCompleted($event->data->object);
        }

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(object $session): void
    {
        $orderId = $session->metadata->order_id ?? null;

        if (!$orderId) {
            return;
        }

        DB::transaction(function () use ($orderId) {
            $order = Order::with('items')->lockForUpdate()->find($orderId);

            if (!$order || $order->status !== 'pending') {
                return;
            }

            foreach ($order->items as $item) {
                $ticket = Ticket::lockForUpdate()->find($item->ticket_id);
                if ($ticket) {
                    $ticket->decrement('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'paid']);
        });
    }
}
