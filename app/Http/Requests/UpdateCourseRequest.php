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
            'code'          => ['required', 'string', 'max:20', Rule::unique('courses', 'code')->ignore($courseId)],
            'title'         => ['required', 'string', 'max:150'],
            'instructor_id' => ['required', 'exists:instructors,id'],
            'fee'           => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique'          => 'This course code is already taken by another course.',
            'instructor_id.exists' => 'The selected instructor does not exist.',
        ];
    }
}