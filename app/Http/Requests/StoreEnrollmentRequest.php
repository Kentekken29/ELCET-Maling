<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// <--- MAKE SURE THIS IS HERE

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:150'],
            'student_id' => ['nullable', 'exists:students,id'],
            'course_name' => ['required', 'string', 'max:150'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'enrollment_date' => 'required|date',
            'discount_applied' => 'nullable|numeric|min:0',
            'completion_percentage' => 'nullable|integer|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'student_name.required' => 'The student name is required.',
            'course_name.required' => 'The course name is required.',
        ];
    }
}
