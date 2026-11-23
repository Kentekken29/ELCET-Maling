<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DomainService
{
    /* ==========================================
     * SANITIZATION HELPERS (Rubric Requirement)
     * ========================================== */

    protected function sanitizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        // Strips HTML tags and trims whitespace
        return trim(strip_tags($value));
    }

    protected function titleCase(string $value): string
    {
        // "juan dela cruz" becomes "Juan Dela Cruz"
        return Str::title($value);
    }

    /* ==========================================
     * COURSE LOGIC
     * ========================================== */

    protected function resolveInstructorId(?string $name = null, ?int $id = null): int
    {
        if ($id !== null) {
            return $id;
        }

        $cleanName = $this->titleCase($this->sanitizeString($name ?? ''));

        if ($cleanName === '') {
            throw new \InvalidArgumentException('Instructor name is required.');
        }

        $instructor = Instructor::query()
            ->whereRaw('LOWER(name) = ?', [Str::lower($cleanName)])
            ->first();

        if (! $instructor) {
            $instructor = Instructor::create([
                'name' => $cleanName,
            ]);
        }

        return $instructor->id;
    }

    public function createCourse(array $data): Course
    {
        return DB::transaction(function () use ($data) {
            return Course::create([
                'code' => strtoupper($this->sanitizeString($data['code'])),
                'title' => $this->sanitizeString($data['title']),
                'instructor_id' => $this->resolveInstructorId($data['instructor_name'] ?? null, $data['instructor_id'] ?? null),
                'fee' => round((float) $data['fee'], 2),
            ]);
        });
    }

    public function updateCourse(int $id, array $data): Course
    {
        return DB::transaction(function () use ($id, $data) {
            $course = Course::findOrFail($id);

            $course->update([
                'code' => isset($data['code']) ? strtoupper($this->sanitizeString($data['code'])) : $course->code,
                'title' => isset($data['title']) ? $this->sanitizeString($data['title']) : $course->title,
                'instructor_id' => $this->resolveInstructorId($data['instructor_name'] ?? null, $data['instructor_id'] ?? $course->instructor_id),
                'fee' => isset($data['fee']) ? round((float) $data['fee'], 2) : $course->fee,
            ]);

            return $course->fresh();
        });
    }

    /* ==========================================
     * INSTRUCTOR LOGIC (For Title-Casing)
     * ========================================== */

    public function createInstructor(array $data): Instructor
    {
        return DB::transaction(function () use ($data) {
            return Instructor::create([
                'name' => $this->titleCase($this->sanitizeString($data['name'])),
            ]);
        });
    }

    /* ==========================================
     * ENROLLMENT LOGIC
     * ========================================== */

    protected function resolveStudentId(?string $name = null, ?int $id = null): int
    {
        if ($id !== null) {
            return $id;
        }

        $cleanName = $this->titleCase($this->sanitizeString($name ?? ''));

        if ($cleanName === '') {
            throw new \InvalidArgumentException('Student name is required.');
        }

        $user = Auth::user();
        if ($user && Str::lower($cleanName) === Str::lower($user->name)) {
            $student = Student::query()
                ->where('user_id', $user->id)
                ->first();

            if (! $student) {
                $student = Student::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_id' => $user->id,
                ]);
            } else {
                $student->update([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);
            }

            return $student->id;
        }

        $student = Student::query()
            ->whereRaw('LOWER(name) = ?', [Str::lower($cleanName)])
            ->first();

        if ($student) {
            return $student->id;
        }

        $slug = Str::slug($cleanName, '');
        $emailBase = $slug !== '' ? $slug : 'student';
        $email = $emailBase.'@example.com';
        $counter = 1;

        while (Student::where('email', $email)->exists()) {
            $email = $emailBase.$counter.'@example.com';
            $counter++;
        }

        return Student::create([
            'name' => $cleanName,
            'email' => $email,
        ])->id;
    }

    protected function resolveCourseId(?string $name = null, ?int $id = null): int
    {
        if ($id !== null) {
            return $id;
        }

        $cleanName = $this->sanitizeString($name ?? '');

        if ($cleanName === '') {
            throw new \InvalidArgumentException('Course name is required.');
        }

        $course = Course::query()
            ->whereRaw('LOWER(title) = ?', [Str::lower($cleanName)])
            ->orWhereRaw('LOWER(code) = ?', [Str::lower($cleanName)])
            ->first();

        if ($course) {
            return $course->id;
        }

        $instructor = Instructor::firstOrCreate([
            'name' => 'Unassigned Instructor',
        ]);

        $codeBase = strtoupper(Str::slug($cleanName, ''));
        $codeBase = preg_replace('/[^A-Z0-9]/', '', $codeBase) ?: 'NEW';
        $codeBase = Str::substr($codeBase, 0, 12);
        $code = $codeBase;
        $counter = 1;

        while (Course::where('code', $code)->exists()) {
            $code = $codeBase.$counter;
            $counter++;
        }

        return Course::create([
            'code' => $code,
            'title' => $cleanName,
            'instructor_id' => $instructor->id,
            'fee' => 0,
        ])->id;
    }

    public function createEnrollment(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            $studentId = $this->resolveStudentId($data['student_name'] ?? null, $data['student_id'] ?? null);
            $courseId = $this->resolveCourseId($data['course_name'] ?? null, $data['course_id'] ?? null);
            $course = Course::findOrFail($courseId);
            $discount = round((float) ($data['discount_applied'] ?? 0), 2);

            if (Enrollment::where('student_id', $studentId)->where('course_id', $courseId)->exists()) {
                throw new \InvalidArgumentException('This student is already enrolled in this course.');
            }

            if ($discount > $course->fee) {
                throw new \InvalidArgumentException('Discount cannot exceed the course fee.');
            }

            return Enrollment::create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'enrollment_date' => $data['enrollment_date'],
                'discount_applied' => $discount,
                'completion_percentage' => (int) ($data['completion_percentage'] ?? 0),
            ]);
        });
    }

    public function updateEnrollment(int $id, array $data): Enrollment
    {
        return DB::transaction(function () use ($id, $data) {
            $enrollment = Enrollment::findOrFail($id);
            $payload = [];

            if (array_key_exists('student_name', $data) || array_key_exists('student_id', $data)) {
                $payload['student_id'] = $this->resolveStudentId($data['student_name'] ?? null, $data['student_id'] ?? $enrollment->student_id);
            }

            if (array_key_exists('course_name', $data) || array_key_exists('course_id', $data)) {
                $payload['course_id'] = $this->resolveCourseId($data['course_name'] ?? null, $data['course_id'] ?? $enrollment->course_id);
            }

            if (array_key_exists('discount_applied', $data) && $data['discount_applied'] !== null) {
                $discount = round((float) $data['discount_applied'], 2);
                if ($discount > $enrollment->course->fee) {
                    throw new \InvalidArgumentException('Discount cannot exceed the course fee.');
                }
                $payload['discount_applied'] = $discount;
            }

            if (array_key_exists('net_amount_paid', $data)) {
                $payload['discount_applied'] = round((float) $enrollment->course->fee - (float) $data['net_amount_paid'], 2);
            }

            if (array_key_exists('completion_percentage', $data) && $data['completion_percentage'] !== null) {
                $payload['completion_percentage'] = (int) $data['completion_percentage'];
            }

            if (array_key_exists('enrollment_date', $data) && $data['enrollment_date']) {
                $payload['enrollment_date'] = $data['enrollment_date'];
            }

            $enrollment->update($payload);

            return $enrollment->fresh();
        });
    }
    /* ==========================================
     * CERTIFICATE BUSINESS RULE (Exam Requirement)
     * ========================================== */

    public function issueCertificate(int $enrollmentId): Certificate
    {
        return DB::transaction(function () use ($enrollmentId) {
            $enrollment = Enrollment::with('certificate')->findOrFail($enrollmentId);

            // BUSINESS RULE: Prevent generation unless completion is 100%
            if ($enrollment->completion_percentage < 100) {
                throw new \DomainException('Certificate cannot be issued until completion reaches 100%.');
            }

            // If it already exists, just return it
            if ($enrollment->certificate) {
                return $enrollment->certificate;
            }

            return Certificate::create([
                'enrollment_id' => $enrollment->id,
                'issued_at' => now(),
            ]);
        });
    }
}
