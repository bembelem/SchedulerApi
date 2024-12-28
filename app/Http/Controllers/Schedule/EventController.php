<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class EventController extends Controller
{
    use ApiResponses;

    public function store(Request $request) : JsonResponse { 
        // Создание события (пары или другой информации)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'teacher' => 'nullable|string|max:255',
            'week_day' => 'required|integer|between:1,7',
            'lesson_number' => 'required|integer|between:1,8',
        ]);
    
        // Проверка на дублирование пары
        if ($request->user()->events()->where([
            ['week_day', $validated['week_day']],
            ['lesson_number', $validated['lesson_number']]
        ])->exists()) {
            return $this->error('Lesson already exists for this time slot');
        }

        $event = $request->user()->events()->create($validated);
        return $this->success('Event was successfully created', $event);
    }

    public function index(Request $request) : JsonResponse { 
        // Получение всех событий пользователя отсортированных по времени
        $events = $request->user()->events()->orderBy('week_day')->orderBy('lesson_number')->get();

        return $this->success('Fetched events successfully', $events);
    }

    public function destroy(Request $request, $id) : JsonResponse {
        $event = $request->user()->events()->find($id);

        if (!$event) {
            return $this->error("Event doesn't exists");
        }

        return $this->success('Event successfully deleted', $event);
    }
}
