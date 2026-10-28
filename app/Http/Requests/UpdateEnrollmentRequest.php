<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'            => ['sometimes', 'required', 'exists:students,id'],
            'course_id'             => ['sometimes', 'required', 'exists:courses,id'],
            'enrollment_date'       => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'discount_applied'      => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'completion_percentage' => ['sometimes', 'nullable', 'integer', 'between:0,100'],
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_date.before_or_equal' => 'The enrollment date cannot be in the future.',
            'completion_percentage.between'   => 'Completion must be a percentage between 0 and 100.',
        ];
    }
}