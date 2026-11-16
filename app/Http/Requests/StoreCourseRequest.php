<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    // Determines if the user is authorized to make this request.
    public function authorize(): bool
    {
        return true; // Set to true for the exam
    }

    // Get the validation rules that apply to the request.
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:courses,code'],
            'title' => ['required', 'string', 'max:150'],
            'instructor_name' => ['required', 'string', 'max:150'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'fee' => ['required', 'numeric', 'min:0'],
        ];
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            'code.unique' => 'This course code already exists in the system.',
            'instructor_name.required' => 'The instructor name is required.',
            'instructor_name.string' => 'The instructor name must be valid text.',
            'instructor_id.exists' => 'The selected instructor does not exist.',
            'fee.numeric' => 'The course fee must be a valid number.',
        ];
    }
}
