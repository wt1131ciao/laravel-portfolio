@extends('layouts.app')

@section('title', '購入完了')

@section('content')
<div class="max-w-xl mx-auto text-center mt-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-10">
        <div class="text-6xl mb-4">✅</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">購入が完了しました</h1>
        <p class="text-gray-500 mb-8">お買い上げありがとうございます。</p>

        @if($order)
            <div class="bg-gray-50 rounded-xl p-5 text-left mb-8">
                <p class="text-sm font-medium text-gray-700 mb-3">注文内容</p>
                @foreach($order->items as $item)
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>{{ $item->ticket->name }} × {{ $item->quantity }}</span>
                        <span>{{ $item->formattedSubtotal() }}</span>
                    </div>
                @endforeach
                <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between font-bold text-gray-900">
                    <span>合計</span>
                    <span>{{ $order->formattedTotal() }}</span>
                </div>
            </div>
        @endif

        <div class="flex gap-3 justify-center">
            <a href="{{ route('orders.index') }}"
               class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                注文履歴を見る
            </a>
            <a href="{{ route('tickets.index') }}"
               class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                引き続き購入する
            </a>
        </div>
    </div>
</div>
@endsection
