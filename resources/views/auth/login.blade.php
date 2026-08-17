@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="max-w-md mx-auto mt-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">ログイン</h1>
        
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-sm text-amber-800 space-y-1.5">
            <p class="font-medium text-amber-900">⚠️ デモ環境のご案内</p>
            <p>テストアカウント: <span class="font-mono">test@example.com / password</span></p>
            <p>Stripeのテストカード番号: <span class="font-mono">4242 4242 4242 4242</span></p>
            <p>決済はテスト環境で行われます。実際のクレジットカード情報は入力しないでください。<br>※仮に入力しても決済は行われませんのでご安心ください。</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">パスワード</label>
                <input type="password" id="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 text-indigo-600">
                <label for="remember" class="ml-2 text-sm text-gray-600">ログイン状態を保持する</label>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                ログイン
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            アカウントをお持ちでない方は
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">新規登録</a>
        </p>
    </div>
</div>
@endsection
