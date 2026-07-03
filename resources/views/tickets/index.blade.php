@extends('layouts.app')

@section('title', 'チケット一覧')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">チケット一覧</h1>
    <p class="mt-2 text-gray-500">お好みのチケットをお選びください</p>
</div>

@if($tickets->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-lg">現在販売中のチケットはありません</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tickets as $ticket)
            <a href="{{ route('tickets.show', $ticket) }}"
               class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

                @if($ticket->image_url)
                    <img src="{{ $ticket->image_url }}" alt="{{ $ticket->name }}"
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <span class="text-5xl">🎫</span>
                    </div>
                @endif

                <div class="p-5">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">{{ $ticket->name }}</h2>

                    @if($ticket->description)
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $ticket->description }}</p>
                    @endif

                    <div class="flex items-center justify-between mt-3">
                        <span class="text-xl font-bold text-indigo-600">{{ $ticket->formattedPrice() }}</span>
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $ticket->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ $ticket->stock > 0 ? '残り ' . $ticket->stock . ' 枚' : '売り切れ' }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
