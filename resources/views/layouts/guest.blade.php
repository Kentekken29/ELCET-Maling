<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') · ELCET Learning</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-background text-foreground">
        <main class="flex min-h-screen items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <a href="{{ route('login') }}" class="mb-8 flex items-center justify-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" />
                        </svg>
                    </span>
                    <span class="text-lg font-semibold tracking-tight">ELCET Learning</span>
                </a>
                @yield('content')
                <p class="mt-8 text-center text-xs text-muted-foreground">Secure learning management workspace</p>
            </div>
        </main>
    </body>
</html>
