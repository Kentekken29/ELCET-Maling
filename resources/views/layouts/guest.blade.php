<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') · Laravel Starter Kit</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-background text-foreground">
        <main class="flex min-h-screen items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <a href="{{ route('login') }}" class="mb-8 flex items-center justify-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                            <path d="M3 6.75 8.25 3l5.25 3.75v7.5L8.25 18 3 14.25v-7.5Z" />
                            <path d="m8.25 10.5 5.25-3.75 7.5 5.25v7.5l-5.25 3.75-7.5-5.25v-7.5Z" />
                            <path d="M13.5 6.75 18.75 3 21 4.5v7.5l-5.25 3.75" />
                        </svg>
                    </span>
                    <span class="text-lg font-semibold tracking-tight">Laravel Starter Kit</span>
                </a>
                @yield('content')
                <p class="mt-8 text-center text-xs text-muted-foreground">Secure learning management workspace</p>
            </div>
        </main>
    </body>
</html>
