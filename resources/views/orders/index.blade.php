@extends('layouts.app')

@section('title', '注文履歴')

@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">注文履歴</h1>

@if($orders->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-lg">注文履歴がありません</p>
        <a href="{{ route('tickets.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline text-sm">
            チケット一覧を見る
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($orders as $order)
            <a href="{{ route('orders.show', $order) }}"
               class="block bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : ($order->status === 'cancelled' ? 'bg-gray-100 text-gray-500' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ match($order->status) {
                                'paid' => '支払い済み',
                                'cancelled' => 'キャンセル',
                                default => '決済待ち',
                            } }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-indigo-600">{{ $order->formattedTotal() }}</span>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->setTimezone('Asia/Tokyo')->format('Y/m/d H:i') }}</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    @foreach($order->items as $item)
                        <span>{{ $item->ticket->name }} × {{ $item->quantity }}</span>@if(!$loop->last), @endif
                    @endforeach
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
