@extends('layouts.app')

@section('title', '注文詳細 #' . $order->id)

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('orders.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-6">
        ← 注文履歴に戻る
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-gray-900">注文 #{{ $order->id }}</h1>
            <span class="px-3 py-1 rounded-full text-sm font-medium
                {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : ($order->status === 'cancelled' ? 'bg-gray-100 text-gray-500' : 'bg-yellow-100 text-yellow-700') }}">
                {{ match($order->status) {
                    'paid' => '支払い済み',
                    'cancelled' => 'キャンセル',
                    default => '決済待ち',
                } }}
            </span>
        </div>

        <p class="text-sm text-gray-400 mb-6">注文日時: {{ $order->created_at->setTimezone('Asia/Tokyo')->format('Y年m月d日 H:i') }}</p>

        <div class="border-t border-gray-100 pt-6 space-y-4">
            @foreach($order->items as $item)
                <div class="flex items-center gap-4">
                    @if($item->ticket->image_url)
                        <img src="{{ $item->ticket->image_url }}" alt="{{ $item->ticket->name }}"
                             class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center text-2xl">
                            🎫
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $item->ticket->name }}</p>
                        <p class="text-sm text-gray-500">{{ '¥' . number_format($item->unit_price) }} × {{ $item->quantity }} 枚</p>
                    </div>
                    <span class="font-bold text-gray-900">{{ $item->formattedSubtotal() }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t border-gray-100 mt-6 pt-6 flex justify-between items-center">
            <span class="font-bold text-gray-900">合計</span>
            <span class="text-xl font-bold text-indigo-600">{{ $order->formattedTotal() }}</span>
        </div>
    </div>
</div>
@endsection
