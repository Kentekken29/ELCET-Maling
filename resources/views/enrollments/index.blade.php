@extends('layouts.app')

@section('title', 'Enrollments')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-sm font-medium text-muted-foreground">Workspace / Enrollments</p>
            <h1 class="text-3xl font-semibold tracking-tight">Enrollments</h1>
            <p class="mt-2 text-sm text-muted-foreground">Track learner registrations and course progress.</p>
        </div>
        <a href="{{ route('enrollments.create') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:opacity-90">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14m-7-7h14" /></svg>
            Add enrollment
        </a>
    </div>

    <section class="overflow-hidden rounded-xl border border-border bg-card shadow-sm">
        <div class="flex items-center justify-between border-b border-border px-5 py-4">
            <div>
                <h2 class="text-base font-semibold">All enrollments</h2>
                <p class="mt-1 text-sm text-muted-foreground">{{ $enrollments->total() }} {{ \Illuminate\Support\Str::plural('record', $enrollments->total()) }} in total</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-muted/50 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th scope="col" class="px-5 py-3">Student</th>
                        <th scope="col" class="px-5 py-3">Course</th>
                        <th scope="col" class="px-5 py-3">Enrolled</th>
                        <th scope="col" class="px-5 py-3">Progress</th>
                        <th scope="col" class="px-5 py-3">Certificate</th>
                        <th scope="col" class="px-5 py-3 text-right">Net fee</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($enrollments as $enrollment)
                        <tr class="transition-colors hover:bg-muted/40">
                            <td class="px-5 py-4">
                                <p class="font-medium">{{ $enrollment->student->name }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">{{ $enrollment->student->email }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-medium">{{ $enrollment->course->title }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">{{ $enrollment->course->code }} · {{ $enrollment->course->instructor->name }}</p>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-muted-foreground">{{ $enrollment->enrollment_date->format('M j, Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-2 w-24 overflow-hidden rounded-full bg-secondary" role="progressbar" aria-label="Course progress" aria-valuenow="{{ $enrollment->completion_percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="h-full rounded-full bg-primary" style="width: {{ $enrollment->completion_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-muted-foreground">{{ $enrollment->completion_percentage }}%</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if ($enrollment->certificate)
                                    <span class="inline-flex items-center rounded-full bg-secondary px-2.5 py-0.5 text-xs font-medium text-secondary-foreground">Issued</span>
                                @else
                                    <span class="text-xs text-muted-foreground">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right font-medium">${{ number_format($enrollment->net_amount_paid, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="font-medium">No enrollments yet</p>
                                <p class="mt-1 text-sm text-muted-foreground">Create an enrollment to start tracking learner progress.</p>
                                <a href="{{ route('enrollments.create') }}" class="mt-4 inline-flex h-9 items-center rounded-md border border-input bg-background px-3 text-sm font-medium shadow-sm hover:bg-accent">Create your first enrollment</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($enrollments->hasPages())
            <div class="border-t border-border px-5 py-4">{{ $enrollments->links() }}</div>
        @endif
    </section>
@endsection
