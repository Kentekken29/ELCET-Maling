<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Overview') · ELCET Learning</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen">
        <div class="min-h-screen lg:flex">
            <aside class="hidden w-64 shrink-0 flex-col border-r border-border bg-card lg:flex">
                <a href="{{ route('dashboard') }}" class="flex h-16 items-center gap-3 border-b border-border px-6">
                    <span class="flex size-9 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" />
                        </svg>
                    </span>
                    <span class="text-sm font-semibold tracking-tight">ELCET Learning</span>
                </a>

                <nav class="flex-1 space-y-6 px-3 py-6" aria-label="Main navigation">
                    <div>
                        <p class="px-3 pb-2 text-xs font-medium uppercase tracking-wide text-muted-foreground">Workspace</p>
                        <div class="space-y-1">
                            <a href="{{ route('dashboard') }}" @class([
                                'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                                'bg-accent text-accent-foreground' => request()->routeIs('dashboard'),
                                'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => ! request()->routeIs('dashboard'),
                            ])>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true">
                                    <rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" />
                                </svg>
                                Overview
                            </a>
                            <a href="{{ route('courses.index') }}" @class([
                                'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                                'bg-accent text-accent-foreground' => request()->routeIs('courses.*'),
                                'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => ! request()->routeIs('courses.*'),
                            ])>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" />
                                </svg>
                                Courses
                            </a>
                            <a href="{{ route('enrollments.index') }}" @class([
                                'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors',
                                'bg-accent text-accent-foreground' => request()->routeIs('enrollments.*'),
                                'text-muted-foreground hover:bg-accent hover:text-accent-foreground' => ! request()->routeIs('enrollments.*'),
                            ])>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
                                </svg>
                                Enrollments
                            </a>
                        </div>
                    </div>
                </nav>

                <div class="border-t border-border p-4">
                    <div class="flex items-center gap-3 rounded-md bg-muted p-3">
                        <span class="flex size-9 items-center justify-center rounded-full bg-background text-xs font-semibold text-muted-foreground">EA</span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">Education Admin</p>
                            <p class="truncate text-xs text-muted-foreground">Learning workspace</p>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="min-w-0 flex-1">
                <header class="flex h-16 items-center justify-between border-b border-border bg-card px-4 sm:px-6 lg:px-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-semibold lg:hidden">
                        <span class="flex size-8 items-center justify-center rounded-md bg-primary text-primary-foreground">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" />
                            </svg>
                        </span>
                        ELCET Learning
                    </a>
                    <p class="hidden text-sm text-muted-foreground lg:block">Learning management</p>
                    <span class="flex size-9 items-center justify-center rounded-full border border-border bg-background text-xs font-semibold">EA</span>
                </header>

                <nav class="flex gap-2 overflow-x-auto border-b border-border bg-card px-4 py-2 lg:hidden" aria-label="Mobile navigation">
                    <a href="{{ route('dashboard') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm {{ request()->routeIs('dashboard') ? 'bg-accent font-medium text-accent-foreground' : 'text-muted-foreground' }}">Overview</a>
                    <a href="{{ route('courses.index') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm {{ request()->routeIs('courses.*') ? 'bg-accent font-medium text-accent-foreground' : 'text-muted-foreground' }}">Courses</a>
                    <a href="{{ route('enrollments.index') }}" class="whitespace-nowrap rounded-md px-3 py-2 text-sm {{ request()->routeIs('enrollments.*') ? 'bg-accent font-medium text-accent-foreground' : 'text-muted-foreground' }}">Enrollments</a>
                </nav>

                <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div role="status" class="mb-6 rounded-lg border border-border bg-card px-4 py-3 text-sm text-foreground shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
