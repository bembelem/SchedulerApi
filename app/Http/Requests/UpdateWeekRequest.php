<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeekRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Здесь можно добавить проверку прав доступа, если необходимо
    }

    public function rules()
    {
        return [
            'week' => 'required|array',
            'week.*' => 'nullable|array',
            'week.*.*.id' => 'nullable|integer',
            'week.*.*.type' => 'nullable|in:practice,lecture',
            'week.*.*.name' => 'nullable|string',
            'week.*.*.room' => 'nullable|string|max:255',
            'week.*.*.teacher' => 'nullable|string|max:255',
            'week.*.*.order' => 'nullable|integer|between:1,8',
            'week.*.*.date' => 'nullable|date',
            'week.*.*.homework' => 'nullable|string|max:255',
            'week.*.*.notes' => 'nullable|string',
            'week.*.*.test' => 'nullable|boolean',
            'week.*.*.project' => 'nullable|boolean',
            'week.*.*.colloquium' => 'nullable|boolean',
        ];
    }
}
