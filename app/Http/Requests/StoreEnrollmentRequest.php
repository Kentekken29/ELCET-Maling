<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'            => ['required', 'exists:students,id'],
            'course_id'             => ['required', 'exists:courses,id'],
            'enrollment_date'       => ['required', 'date', 'before_or_equal:today'],
            'discount_applied'      => ['nullable', 'numeric', 'min:0'],
            'completion_percentage' => ['nullable', 'integer', 'between:0,100'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.exists'               => 'The selected student does not exist.',
            'course_id.exists'                => 'The selected course does not exist.',
            'enrollment_date.before_or_equal' => 'The enrollment date cannot be in the future.',
            'completion_percentage.between'   => 'Completion must be a percentage between 0 and 100.',
        ];
    }
}