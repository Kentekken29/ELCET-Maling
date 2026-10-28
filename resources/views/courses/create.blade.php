@extends('layouts.app')

@section('title', 'Add course')

@section('content')
    <div class="mb-8">
        <a href="{{ route('courses.index') }}" class="text-sm text-muted-foreground hover:text-foreground">← Back to courses</a>
        <p class="mb-2 mt-6 text-sm font-medium text-muted-foreground">Workspace / Courses / New</p>
        <h1 class="text-3xl font-semibold tracking-tight">Create course</h1>
        <p class="mt-2 text-sm text-muted-foreground">Add a new course to your learning catalog.</p>
    </div>

    <form method="POST" action="{{ route('courses.store') }}" class="max-w-3xl space-y-6">
        @csrf
        <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="font-semibold">Course details</h2>
                <p class="mt-1 text-sm text-muted-foreground">Enter the course information and assign an instructor.</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="space-y-2">
                    <label for="code" class="text-sm font-medium">Course code</label>
                    <input id="code" name="code" value="{{ old('code') }}" required maxlength="20" placeholder="e.g. WEB-101" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    @error('code') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="title" class="text-sm font-medium">Course title</label>
                    <input id="title" name="title" value="{{ old('title') }}" required maxlength="150" placeholder="e.g. Introduction to Web Design" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    @error('title') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="instructor_id" class="text-sm font-medium">Instructor</label>
                    <select id="instructor_id" name="instructor_id" required class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        <option value="">Select an instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @selected(old('instructor_id') == $instructor->id)>{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                    @error('instructor_id') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="fee" class="text-sm font-medium">Course fee</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-sm text-muted-foreground">$</span>
                        <input id="fee" name="fee" type="number" value="{{ old('fee') }}" required min="0" step="0.01" placeholder="0.00" class="h-10 w-full rounded-md border border-input bg-background py-2 pl-7 pr-3 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                    @error('fee') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('courses.index') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm hover:bg-accent">Cancel</a>
            <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm hover:opacity-90">Create course</button>
        </div>
    </form>
@endsection
