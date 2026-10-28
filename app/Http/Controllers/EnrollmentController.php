<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use App\Services\DomainService;

class EnrollmentController extends Controller
{
    // Inject the DomainService via the constructor
    public function __construct(protected DomainService $service) {}

    // 1. Retrieve paginated records with eager-loaded relationships
    public function index()
    {
        // Eager-loading student, course (and its instructor), and certificate
        $enrollments = Enrollment::with(['student', 'course.instructor', 'certificate'])
                                ->latest('enrollment_date')
                                ->paginate(15);
                                
        return view('enrollments.index', compact('enrollments'));
    }

    // 2. Return creation interface or required dependency data
    public function create()
    {
        return view('enrollments.create', [
            'students' => Student::orderBy('name')->get(),
            'courses'  => Course::with('instructor')->orderBy('title')->get(),
        ]);
    }

    // 3. Validate and delegate to Service class
    public function store(StoreEnrollmentRequest $request)
    {
        $this->service->createEnrollment($request->validated());
        return redirect()->route('enrollments.index')->with('success', 'Enrollment created successfully.');
    }

    // 4. Validate and delegate data updates to Service class
    public function update(UpdateEnrollmentRequest $request, $id)
    {
        $this->service->updateEnrollment((int)$id, $request->validated());
        return redirect()->route('enrollments.index')->with('success', 'Enrollment updated successfully.');
    }
}