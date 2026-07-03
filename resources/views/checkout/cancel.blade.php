@extends('layouts.app')

@section('title', '購入キャンセル')

@section('content')
<div class="max-w-xl mx-auto text-center mt-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-10">
        <div class="text-6xl mb-4">❌</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">購入をキャンセルしました</h1>
        <p class="text-gray-500 mb-8">決済がキャンセルされました。引き続きご利用いただけます。</p>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('tickets.index') }}"
               class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                チケット一覧へ戻る
            </a>
        </div>
    </div>
</div>
@endsection
