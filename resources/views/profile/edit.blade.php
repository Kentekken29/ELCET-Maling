@extends('layouts.app')

@section('title', 'Profile settings')

@section('content')
    <div class="mb-8">
        <p class="mb-2 text-sm font-medium text-muted-foreground">Workspace / Account</p>
        <h1 class="text-3xl font-semibold tracking-tight">Profile settings</h1>
        <p class="mt-2 text-sm text-muted-foreground">Manage your account details and password.</p>
    </div>

    @if (session('password_success'))
        <div role="status" class="mb-6 rounded-lg border border-border bg-card px-4 py-3 text-sm text-foreground shadow-sm">
            {{ session('password_success') }}
        </div>
    @endif

    <div class="grid max-w-4xl gap-6">
        <section class="rounded-xl border border-border bg-card p-6 shadow-sm sm:p-8">
            <div class="mb-6">
                <h2 class="font-semibold">Personal information</h2>
                <p class="mt-1 text-sm text-muted-foreground">Update the name and email address associated with your account.</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium">Full name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required maxlength="255" autocomplete="name" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        @error('name') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required maxlength="255" autocomplete="email" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        @error('email') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-opacity hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">Save changes</button>
                </div>
            </form>
        </section>

        <section class="rounded-xl border border-border bg-card p-6 shadow-sm sm:p-8">
            <div class="mb-6">
                <h2 class="font-semibold">Change password</h2>
                <p class="mt-1 text-sm text-muted-foreground">Enter your current password before choosing a new one.</p>
            </div>

            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="space-y-2 sm:col-span-2">
                        <label for="current_password" class="text-sm font-medium">Current password</label>
                        <input id="current_password" name="current_password" type="password" required autocomplete="current-password" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        @error('current_password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium">New password</label>
                        <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        <p class="text-xs text-muted-foreground">Use at least 8 characters.</p>
                        @error('password') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-medium">Confirm new password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm transition-colors hover:bg-accent focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">Update password</button>
                </div>
            </form>
        </section>
    </div>
@endsection
