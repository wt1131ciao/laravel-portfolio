@extends('layouts.admin')

@section('title', '注文一覧')

@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">注文一覧</h1>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">注文ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ユーザー</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">内容</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">合計</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">状態</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日時</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                        @foreach($order->items as $item)
                            <span>{{ $item->ticket->name }} ×{{ $item->quantity }}</span>@if(!$loop->last)<br>@endif
                        @endforeach
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $order->formattedTotal() }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : ($order->status === 'cancelled' ? 'bg-gray-100 text-gray-500' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ match($order->status) {
                                'paid' => '支払い済み',
                                'cancelled' => 'キャンセル',
                                default => '決済待ち',
                            } }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $order->created_at->setTimezone('Asia/Tokyo')->format('Y/m/d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">注文がありません</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endif
@endsection
