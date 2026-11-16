@extends('layouts.guest')

@section('title', 'Sign up')

@section('content')
    <section class="rounded-xl border border-border bg-card p-6 shadow-sm sm:p-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-muted-foreground">Get started</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight">Create your account</h1>
            <p class="mt-2 text-sm text-muted-foreground">Sign up to access the learning workspace.</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
            @csrf
            <div class="space-y-2">
                <label for="name" class="text-sm font-medium">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus maxlength="255" autocomplete="name" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                @error('name') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label for="email" class="text-sm font-medium">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                @error('email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label for="password" class="text-sm font-medium">Password</label>
                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                <p class="text-xs text-muted-foreground">Use at least 8 characters.</p>
                @error('password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-2">
                <label for="password_confirmation" class="text-sm font-medium">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
            </div>
            <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                Create account
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-muted-foreground">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-foreground underline-offset-4 hover:underline">Log in</a>
        </p>
    </section>
@endsection
