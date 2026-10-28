<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DomainService
{
    /* ==========================================
     * SANITIZATION HELPERS (Rubric Requirement)
     * ========================================== */
    
    protected function sanitizeString(?string $value): ?string
    {
        if ($value === null) return null;
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

    public function createCourse(array $data): Course
    {
        return DB::transaction(function () use ($data) {
            return Course::create([
                'code'          => strtoupper($this->sanitizeString($data['code'])),
                'title'         => $this->sanitizeString($data['title']),
                'instructor_id' => $data['instructor_id'],
                'fee'           => round((float)$data['fee'], 2),
            ]);
        });
    }

    public function updateCourse(int $id, array $data): Course
    {
        return DB::transaction(function () use ($id, $data) {
            $course = Course::findOrFail($id);
            
            $course->update([
                'code'          => isset($data['code']) ? strtoupper($this->sanitizeString($data['code'])) : $course->code,
                'title'         => isset($data['title']) ? $this->sanitizeString($data['title']) : $course->title,
                'instructor_id' => $data['instructor_id'] ?? $course->instructor_id,
                'fee'           => isset($data['fee']) ? round((float)$data['fee'], 2) : $course->fee,
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

    public function createEnrollment(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            $course   = Course::findOrFail($data['course_id']);
            $discount = round((float)($data['discount_applied'] ?? 0), 2);

            // Business Rule: Discount cannot be greater than the course fee
            if ($discount > $course->fee) {
                throw new \InvalidArgumentException('Discount cannot exceed the course fee.');
            }

            return Enrollment::create([
                'student_id'            => $data['student_id'],
                'course_id'             => $data['course_id'],
                'enrollment_date'       => $data['enrollment_date'],
                'discount_applied'      => $discount,
                'completion_percentage' => (int)($data['completion_percentage'] ?? 0),
            ]);
        });
    }

    public function updateEnrollment(int $id, array $data): Enrollment
    {
        return DB::transaction(function () use ($id, $data) {
            $enrollment = Enrollment::findOrFail($id);
            $payload = [];

            if (array_key_exists('discount_applied', $data) && $data['discount_applied'] !== null) {
                $discount = round((float)$data['discount_applied'], 2);
                if ($discount > $enrollment->course->fee) {
                    throw new \InvalidArgumentException('Discount cannot exceed the course fee.');
                }
                $payload['discount_applied'] = $discount;
            }

            if (array_key_exists('completion_percentage', $data) && $data['completion_percentage'] !== null) {
                $payload['completion_percentage'] = (int)$data['completion_percentage'];
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
                'issued_at'     => now(),
            ]);
        });
    }
}