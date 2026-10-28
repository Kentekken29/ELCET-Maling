@extends('layouts.app')

@section('title', 'Overview')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-sm font-medium text-muted-foreground">Workspace / Overview</p>
            <h1 class="text-3xl font-semibold tracking-tight">Welcome back</h1>
            <p class="mt-2 text-sm text-muted-foreground">Here’s what’s happening across your learning programs.</p>
        </div>
        <a href="{{ route('enrollments.create') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:opacity-90">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14m-7-7h14" /></svg>
            Add enrollment
        </a>
    </div>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" aria-label="Workspace statistics">
        @foreach ([
            ['label' => 'Total courses', 'value' => $stats['courses'], 'hint' => 'Available in your catalog', 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z'],
            ['label' => 'Students', 'value' => $stats['students'], 'hint' => 'Registered learners', 'icon' => 'M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z'],
            ['label' => 'Enrollments', 'value' => $stats['enrollments'], 'hint' => 'Course registrations', 'icon' => 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01'],
        ] as $stat)
            <article class="rounded-xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-muted-foreground">{{ $stat['label'] }}</p>
                    <span class="flex size-9 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" /></svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-semibold tracking-tight">{{ number_format($stat['value']) }}</p>
                <p class="mt-1 text-xs text-muted-foreground">{{ $stat['hint'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="mt-8 rounded-xl border border-border bg-card shadow-sm">
        <div class="flex flex-col gap-3 border-b border-border px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold">Recent enrollments</h2>
                <p class="mt-1 text-sm text-muted-foreground">The latest students added to a course.</p>
            </div>
            <a href="{{ route('enrollments.index') }}" class="text-sm font-medium text-foreground hover:underline">View all enrollments <span aria-hidden="true">→</span></a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-muted/50 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th scope="col" class="px-5 py-3">Student</th>
                        <th scope="col" class="px-5 py-3">Course</th>
                        <th scope="col" class="px-5 py-3">Enrolled</th>
                        <th scope="col" class="px-5 py-3 text-right">Progress</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($recentEnrollments as $enrollment)
                        <tr class="transition-colors hover:bg-muted/40">
                            <td class="px-5 py-4 font-medium">{{ $enrollment->student->name }}</td>
                            <td class="px-5 py-4 text-muted-foreground">{{ $enrollment->course->title }}</td>
                            <td class="px-5 py-4 text-muted-foreground">{{ $enrollment->enrollment_date->format('M j, Y') }}</td>
                            <td class="px-5 py-4 text-right font-medium">{{ $enrollment->completion_percentage }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <p class="font-medium">No enrollments yet</p>
                                <p class="mt-1 text-sm text-muted-foreground">Add an enrollment to see learner activity here.</p>
                                <a href="{{ route('enrollments.create') }}" class="mt-4 inline-flex h-9 items-center rounded-md border border-input bg-background px-3 text-sm font-medium shadow-sm hover:bg-accent">Create enrollment</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
