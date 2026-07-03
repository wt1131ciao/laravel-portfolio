@extends('layouts.app')

@section('title', $ticket->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('tickets.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-6">
        ← チケット一覧に戻る
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        @if($ticket->image_url)
            <img src="{{ $ticket->image_url }}" alt="{{ $ticket->name }}" class="w-full h-64 object-cover">
        @else
            <div class="w-full h-64 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                <span class="text-7xl">🎫</span>
            </div>
        @endif

        <div class="p-8">
            <div class="flex items-start justify-between mb-4">
                <h1 class="text-3xl font-bold text-gray-900">{{ $ticket->name }}</h1>
                <span class="text-2xl font-bold text-indigo-600 ml-4 whitespace-nowrap">{{ $ticket->formattedPrice() }}</span>
            </div>

            @if($ticket->description)
                <p class="text-gray-600 mb-6 leading-relaxed">{{ $ticket->description }}</p>
            @endif

            <div class="flex items-center gap-2 mb-8">
                @if($ticket->stock > 0)
                    <span class="inline-flex items-center gap-1 text-sm text-green-700 bg-green-50 border border-green-200 rounded-full px-3 py-1">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        残り {{ $ticket->stock }} 枚
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-sm text-red-600 bg-red-50 border border-red-200 rounded-full px-3 py-1">
                        売り切れ
                    </span>
                @endif
            </div>

            @if($ticket->isAvailable())
                @auth
                    <form method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                        <div class="flex items-end gap-4">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">枚数</label>
                                <select id="quantity" name="quantity"
                                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @for($i = 1; $i <= min(10, $ticket->stock); $i++)
                                        <option value="{{ $i }}">{{ $i }} 枚</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-8 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                                Stripeで購入する
                            </button>
                        </div>
                    </form>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                        <p class="text-gray-600 mb-4">購入にはログインが必要です</p>
                        <div class="flex gap-3 justify-center">
                            <a href="{{ route('login') }}"
                               class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                                ログイン
                            </a>
                            <a href="{{ route('register') }}"
                               class="border border-indigo-600 text-indigo-600 px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-50 transition-colors">
                                新規登録
                            </a>
                        </div>
                    </div>
                @endauth
            @else
                <button disabled
                    class="bg-gray-200 text-gray-400 px-8 py-2.5 rounded-lg font-medium cursor-not-allowed w-full">
                    現在購入できません
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
