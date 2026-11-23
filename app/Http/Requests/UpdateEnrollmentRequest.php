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
            'student_name' => ['sometimes', 'required', 'string', 'max:150'],
            'student_id' => ['sometimes', 'nullable', 'exists:students,id'],
            'course_name' => ['sometimes', 'required', 'string', 'max:150'],
            'course_id' => ['sometimes', 'nullable', 'exists:courses,id'],
            'enrollment_date' => ['sometimes', 'required', 'date'],
            'discount_applied' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'net_amount_paid' => ['sometimes', 'required', 'numeric', 'min:0', 'max:100000'],
            'completion_percentage' => ['sometimes', 'nullable', 'integer', 'between:0,100'],
        ];
    }

    public function messages(): array
    {
        return [
            'completion_percentage.between' => 'Completion must be a percentage between 0 and 100.',
            'net_amount_paid.min' => 'The net fee cannot be negative.',
            'net_amount_paid.max' => 'The net fee cannot exceed ₱100,000.00.',
        ];
    }
}
