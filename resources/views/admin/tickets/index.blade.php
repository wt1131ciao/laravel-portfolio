@extends('layouts.admin')

@section('title', 'チケット管理')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">チケット管理</h1>
    <a href="{{ route('admin.tickets.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
        + チケットを追加
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">チケット</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">価格</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">在庫</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">状態</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($ticket->image_url)
                                <img src="{{ $ticket->image_url }}" alt="{{ $ticket->name }}"
                                     class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-lg">🎫</div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $ticket->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 max-w-xs truncate">{{ $ticket->description }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $ticket->formattedPrice() }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($ticket->stock) }} 枚</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $ticket->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                            {{ $ticket->is_active ? '販売中' : '非公開' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.tickets.edit', $ticket) }}"
                               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">編集</a>
                            <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}"
                                  onsubmit="return confirm('「{{ $ticket->name }}」を削除してよろしいですか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">削除</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                        チケットがありません
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($tickets->hasPages())
    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
@endif
@endsection
