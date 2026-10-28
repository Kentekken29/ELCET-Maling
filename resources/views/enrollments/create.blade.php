@extends('layouts.app')

@section('title', 'Add enrollment')

@section('content')
    <div class="mb-8">
        <a href="{{ route('enrollments.index') }}" class="text-sm text-muted-foreground hover:text-foreground">← Back to enrollments</a>
        <p class="mb-2 mt-6 text-sm font-medium text-muted-foreground">Workspace / Enrollments / New</p>
        <h1 class="text-3xl font-semibold tracking-tight">Create enrollment</h1>
        <p class="mt-2 text-sm text-muted-foreground">Register a student and set their current course progress.</p>
    </div>

    <form method="POST" action="{{ route('enrollments.store') }}" class="max-w-3xl space-y-6">
        @csrf
        <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="font-semibold">Enrollment details</h2>
                <p class="mt-1 text-sm text-muted-foreground">Choose the student and course for this registration.</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="space-y-2">
                    <label for="student_id" class="text-sm font-medium">Student</label>
                    <select id="student_id" name="student_id" required class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        <option value="">Select a student</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->name }} · {{ $student->email }}</option>
                        @endforeach
                    </select>
                    @error('student_id') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="course_id" class="text-sm font-medium">Course</label>
                    <select id="course_id" name="course_id" required class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        <option value="">Select a course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->code }} · {{ $course->title }}</option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="enrollment_date" class="text-sm font-medium">Enrollment date</label>
                    <input id="enrollment_date" name="enrollment_date" type="date" value="{{ old('enrollment_date', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    @error('enrollment_date') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="discount_applied" class="text-sm font-medium">Discount applied</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-sm text-muted-foreground">$</span>
                        <input id="discount_applied" name="discount_applied" type="number" value="{{ old('discount_applied', '0.00') }}" min="0" step="0.01" class="h-10 w-full rounded-md border border-input bg-background py-2 pl-7 pr-3 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>
                    @error('discount_applied') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2 sm:col-span-2">
                    <label for="completion_percentage" class="text-sm font-medium">Course progress</label>
                    <div class="relative">
                        <input id="completion_percentage" name="completion_percentage" type="number" value="{{ old('completion_percentage', 0) }}" min="0" max="100" step="1" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 pr-9 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        <span class="absolute inset-y-0 right-3 flex items-center text-sm text-muted-foreground">%</span>
                    </div>
                    @error('completion_percentage') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('enrollments.index') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium shadow-sm hover:bg-accent">Cancel</a>
            <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm hover:opacity-90">Create enrollment</button>
        </div>
    </form>
@endsection
