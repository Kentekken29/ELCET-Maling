@extends('layouts.guest')

@section('title', 'Log in')

@section('content')
    <section class="rounded-xl border border-border bg-card p-6 shadow-sm sm:p-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-muted-foreground">Welcome back</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight">Log in to your account</h1>
            <p class="mt-2 text-sm text-muted-foreground">Enter your email and password to continue.</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf
            <div class="space-y-2">
                <label for="email" class="text-sm font-medium">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                @error('email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label for="password" class="text-sm font-medium">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                @error('password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
            </div>
            <label for="remember" class="flex items-center gap-2 text-sm text-muted-foreground">
                <input id="remember" name="remember" type="checkbox" value="1" class="size-4 rounded border-input accent-primary">
                Remember me
            </label>
            <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                Log in
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-muted-foreground">
            Don’t have an account?
            <a href="{{ route('register') }}" class="font-medium text-foreground underline-offset-4 hover:underline">Sign up</a>
        </p>
    </section>
@endsection
