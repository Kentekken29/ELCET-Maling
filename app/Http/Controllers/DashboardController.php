<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'courses' => Course::count(),
            'students' => Student::count(),
            'enrollments' => Enrollment::count(),
        ];
        $recentEnrollments = Enrollment::with(['student', 'course'])
            ->latest('enrollment_date')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentEnrollments'));
    }
}
