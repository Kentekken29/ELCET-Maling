<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Get the course ID from the route (e.g., /courses/{course})
        $courseId = $this->route('course');

        return [
            'code' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('courses', 'code')->ignore($courseId)],
            'title' => ['sometimes', 'required', 'string', 'max:150'],
            'instructor_name' => ['sometimes', 'required', 'string', 'max:150'],
            'instructor_id' => ['sometimes', 'nullable', 'exists:instructors,id'],
            'fee' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'This course code is already taken by another course.',
            'instructor_name.required' => 'The instructor name is required.',
            'instructor_name.string' => 'The instructor name must be valid text.',
            'instructor_id.exists' => 'The selected instructor does not exist.',
            'fee.numeric' => 'The course fee must be a valid number.',
            'fee.min' => 'The course fee cannot be negative.',
            'fee.max' => 'The course fee cannot exceed ₱99,999,999.99.',
        ];
    }
}
