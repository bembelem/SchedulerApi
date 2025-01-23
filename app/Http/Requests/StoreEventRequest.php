<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'type' => 'nullable|in:practice,lecture',
            'name' => 'required|string',
            'room' => 'required|string|max:255',
            'teacher' => 'nullable|string|max:255',
            'order' => 'required|integer|between:1,8',
            'date' => 'required|date',
            'homework' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'test' => 'nullable|boolean',
            'project' => 'nullable|boolean',
            'colloquium' => 'nullable|boolean',
        ];
    }
}
