<?php

namespace App\Http\Requests;

use App\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'enrollment_date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'discount_applied' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'net_amount_paid' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'completion_percentage' => ['sometimes', 'nullable', 'integer', 'between:0,100'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->exists('net_amount_paid') || $validator->errors()->has('net_amount_paid')) {
                    return;
                }

                $enrollment = Enrollment::query()
                    ->with('course')
                    ->find($this->route('enrollment'));

                if ($enrollment && (float) $this->input('net_amount_paid') > (float) $enrollment->course->fee) {
                    $validator->errors()->add('net_amount_paid', 'The net fee cannot exceed the course fee.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_date.before_or_equal' => 'The enrollment date cannot be in the future.',
            'completion_percentage.between' => 'Completion must be a percentage between 0 and 100.',
            'net_amount_paid.min' => 'The net fee cannot be negative.',
            'net_amount_paid.max' => 'The net fee cannot exceed ₱99,999,999.99.',
        ];
    }
}
