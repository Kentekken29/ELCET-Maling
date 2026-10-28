<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Instructor;
use App\Services\DomainService;

class CourseController extends Controller
{
    // Inject the DomainService via the constructor
    public function __construct(protected DomainService $service) {}

    // 1. Retrieve paginated records with eager-loaded relationships
    public function index()
    {
        $courses = Course::with('instructor')
                        ->withCount('enrollments')
                        ->paginate(15);
                        
        return view('courses.index', compact('courses'));
    }

    // 2. Return creation interface or required dependency data
    public function create()
    {
        $instructors = Instructor::orderBy('name')->get();
        return view('courses.create', compact('instructors'));
    }

    // 3. Validate and delegate to Service class
    public function store(StoreCourseRequest $request)
    {
        $this->service->createCourse($request->validated());
        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    // 4. Validate and delegate data updates to Service class
    public function update(UpdateCourseRequest $request, $id)
    {
        $this->service->updateCourse((int)$id, $request->validated());
        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }
}