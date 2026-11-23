<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];

        if ($this->has('code') && is_string($this->input('code'))) {
            $normalized['code'] = strtoupper(trim($this->input('code')));
        }

        if ($this->has('title') && is_string($this->input('title'))) {
            $normalized['title'] = trim($this->input('title'));
        }

        if ($this->has('instructor_name') && is_string($this->input('instructor_name'))) {
            $normalized['instructor_name'] = trim($this->input('instructor_name'));
        }

        if ($normalized !== []) {
            $this->merge($normalized);
        }
    }

    public function rules(): array
    {
        return [
            'code' => ['sometimes', 'required', 'string', 'max:20'],
            'title' => ['sometimes', 'required', 'string', 'max:150'],
            'instructor_name' => ['sometimes', 'required', 'string', 'max:150'],
            'instructor_id' => ['sometimes', 'nullable', 'exists:instructors,id'],
            'fee' => ['required', 'numeric', 'min:0', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return [
            'instructor_name.required' => 'The instructor name is required.',
            'instructor_name.string' => 'The instructor name must be valid text.',
            'instructor_id.exists' => 'The selected instructor does not exist.',
            'fee.numeric' => 'The course fee must be a valid number.',
            'fee.min' => 'The course fee cannot be negative.',
            'fee.max' => 'The course fee cannot exceed ₱100,000.00.',
        ];
    }
}
