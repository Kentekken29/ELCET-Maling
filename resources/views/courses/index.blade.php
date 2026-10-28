@extends('layouts.app')

@section('title', 'Courses')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-sm font-medium text-muted-foreground">Workspace / Courses</p>
            <h1 class="text-3xl font-semibold tracking-tight">Courses</h1>
            <p class="mt-2 text-sm text-muted-foreground">Manage your catalog and course instructors.</p>
        </div>
        <a href="{{ route('courses.create') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:opacity-90">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14m-7-7h14" /></svg>
            Add course
        </a>
    </div>

    <section class="overflow-hidden rounded-xl border border-border bg-card shadow-sm">
        <div class="flex items-center justify-between border-b border-border px-5 py-4">
            <div>
                <h2 class="text-base font-semibold">Course catalog</h2>
                <p class="mt-1 text-sm text-muted-foreground">{{ $courses->total() }} {{ \Illuminate\Support\Str::plural('course', $courses->total()) }} in total</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-muted/50 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th scope="col" class="px-5 py-3">Course</th>
                        <th scope="col" class="px-5 py-3">Instructor</th>
                        <th scope="col" class="px-5 py-3">Students</th>
                        <th scope="col" class="px-5 py-3 text-right">Fee</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($courses as $course)
                        <tr class="transition-colors hover:bg-muted/40">
                            <td class="px-5 py-4">
                                <p class="font-medium">{{ $course->title }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">{{ $course->code }}</p>
                            </td>
                            <td class="px-5 py-4 text-muted-foreground">{{ $course->instructor->name }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full bg-secondary px-2.5 py-0.5 text-xs font-medium text-secondary-foreground">{{ $course->enrollments_count }}</span>
                            </td>
                            <td class="px-5 py-4 text-right font-medium">${{ number_format((float) $course->fee, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <p class="font-medium">Your catalog is empty</p>
                                <p class="mt-1 text-sm text-muted-foreground">Create a course to start building your learning catalog.</p>
                                <a href="{{ route('courses.create') }}" class="mt-4 inline-flex h-9 items-center rounded-md border border-input bg-background px-3 text-sm font-medium shadow-sm hover:bg-accent">Create your first course</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($courses->hasPages())
            <div class="border-t border-border px-5 py-4">{{ $courses->links() }}</div>
        @endif
    </section>
@endsection
