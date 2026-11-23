<?php

namespace App\Http\Requests;

use App\Models\Instructor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class StoreCourseRequest extends FormRequest
{
    // Determines if the user is authorized to make this request.
    public function authorize(): bool
    {
        return true; // Set to true for the exam
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => is_string($this->input('code'))
                ? strtoupper(trim($this->input('code')))
                : $this->input('code'),
            'title' => is_string($this->input('title'))
                ? trim($this->input('title'))
                : $this->input('title'),
            'instructor_name' => is_string($this->input('instructor_name'))
                ? trim($this->input('instructor_name'))
                : $this->input('instructor_name'),
        ]);
    }

    // Get the validation rules that apply to the request.
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20'],
            'title' => ['required', 'string', 'max:150'],
            'instructor_name' => ['required', 'string', 'max:150'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'fee' => ['required', 'numeric', 'min:0', 'max:100000'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('instructor_name')) {
                    return;
                }

                $name = trim((string) $this->input('instructor_name'));

                if ($name === '') {
                    return;
                }

                $exists = Instructor::query()
                    ->whereRaw('LOWER(name) = ?', [Str::lower($name)])
                    ->exists();

                if ($exists) {
                    $validator->errors()->forget('code');
                    $validator->errors()->add(
                        'instructor_name',
                        'This instructor already exists in the system.'
                    );
                }
            },
        ];
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            'instructor_name.required' => 'The instructor name is required.',
            'instructor_name.string' => 'The instructor name must be valid text.',
            'instructor_id.exists' => 'The selected instructor does not exist.',
            'fee.numeric' => 'The course fee must be a valid number.',
            'fee.max' => 'The course fee cannot exceed ₱100,000.00.',
        ];
    }
}
