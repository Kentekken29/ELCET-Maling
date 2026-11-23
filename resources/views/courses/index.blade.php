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
        @error('fee')
            <p role="alert" class="border-b border-destructive/20 bg-destructive/5 px-5 py-3 text-sm text-destructive">{{ $message }}</p>
        @enderror
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
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('courses.update', $course) }}" class="flex flex-col items-end gap-2" data-inline-fee-form>
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center justify-end gap-2">
                                        <span class="font-medium tabular-nums" data-fee-display>₱{{ number_format((float) $course->fee, 2) }}</span>
                                        <div id="fee-editor-{{ $course->id }}" class="relative w-36" data-fee-editor hidden>
                                            <span class="absolute inset-y-0 left-3 flex items-center text-sm text-muted-foreground">₱</span>
                                            <label for="fee-{{ $course->id }}" class="sr-only">Course fee for {{ $course->title }}</label>
                                            <input id="fee-{{ $course->id }}" name="fee" type="number" value="{{ $course->fee }}" required min="0" max="100000" step="0.01" inputmode="decimal" class="h-9 w-full rounded-md border border-input bg-background py-2 pl-7 pr-3 text-right text-sm tabular-nums shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" data-fee-input>
                                        </div>
                                        <button type="button" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-input bg-background px-2.5 text-xs font-medium text-foreground shadow-sm transition-colors hover:bg-accent focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" aria-label="Edit fee for {{ $course->title }}" aria-expanded="false" aria-controls="fee-editor-{{ $course->id }}" data-fee-edit>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 2.651 2.651M7.5 16.5l.75-3.75L17.99 3.01a2.121 2.121 0 0 1 3 3L11.25 15.75 7.5 16.5ZM5 20h14" /></svg>
                                            Edit
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-end gap-2" data-fee-actions hidden>
                                        <button type="submit" class="inline-flex h-8 items-center justify-center rounded-md bg-primary px-3 text-xs font-medium text-primary-foreground shadow-sm transition-colors hover:opacity-90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">Save</button>
                                        <button type="button" class="inline-flex h-8 items-center justify-center rounded-md border border-input bg-background px-3 text-xs font-medium text-foreground shadow-sm transition-colors hover:bg-accent focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" data-fee-cancel>Cancel</button>
                                    </div>
                                </form>
                            </td>
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
