<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '管理画面') | TicketShop Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Noto Sans JP"', 'ui-sans-serif', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14 items-center">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.tickets.index') }}" class="font-bold text-white">
                    TicketShop <span class="text-gray-400 font-normal text-sm">管理画面</span>
                </a>
                <a href="{{ route('admin.tickets.index') }}" class="text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.tickets.*') ? 'text-white font-medium' : '' }}">
                    チケット管理
                </a>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.orders.*') ? 'text-white font-medium' : '' }}">
                    注文一覧
                </a>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.users.*') ? 'text-white font-medium' : '' }}">
                    ユーザー管理
                </a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('tickets.index') }}" class="text-sm text-gray-400 hover:text-white">← サイトへ戻る</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-400 hover:text-red-400">ログアウト</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

</body>
</html>
