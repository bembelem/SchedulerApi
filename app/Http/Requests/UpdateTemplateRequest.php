<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'timetable' => 'nullable|array',
            'timetable.*' => 'nullable|array',
            'timetable.*.*.type' => 'nullable|in:practice,lecture',
            'timetable.*.*.room' => 'nullable|string|max:255',
            'timetable.*.*.teacher' => 'nullable|string|max:255',
            'timetable.*.*.order' => 'nullable|integer|between:1,8',
            'timetable.*.*.homework' => 'nullable|string|max:255',
            'timetable.*.*.notes' => 'nullable|string',
            'timetable.*.*.test' => 'nullable|boolean',
            'timetable.*.*.project' => 'nullable|boolean',
            'timetable.*.*.colloquium' => 'nullable|boolean',
        ];
    }
}
