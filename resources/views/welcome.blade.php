<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Manager</title>

    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    <div class="min-h-screen flex flex-col justify-center items-center p-6">
        <div class="bg-white shadow-lg rounded-xl w-full max-w-md p-8 text-center">
            <h1 class="text-2xl font-bold mb-4">🎉 Event Management App</h1>
            <p class="text-gray-600 mb-6">Easily create, manage, and get reminders for your upcoming events.</p>

            @if (Route::has('login'))
                <div class="flex justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <footer class="mt-8 text-sm text-gray-500">
            &copy; {{ date('Y') }} Event Manager. All rights reserved.
        </footer>
    </div>

</body>
</html>
