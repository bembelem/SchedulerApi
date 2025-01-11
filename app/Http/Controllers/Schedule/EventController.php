<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use GrahamCampbell\ResultType\Success;

use function Laravel\Prompts\error;

class EventController extends Controller
{
    use ApiResponses;

    public function store(Request $request) : JsonResponse { 
        // Создание события (пары или другой информации)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'teacher' => 'nullable|string|max:255',
            'lesson_number' => 'required|integer|between:1,8',
            'date' => 'required|date'
        ]);
    
        // Проверка на дублирование пары
        if ($request->user()->events()->where([
            ['date', $validated['date']],
            ['lesson_number', $validated['lesson_number']]
            ])->exists()) {
            return $this->error(message:'Lesson already exists for this time slot');
        }

        $event = $request->user()->events()->create($validated);
        return $this->success('Event was successfully created', $event);
    }

    public function index(Request $request) : JsonResponse { 
        // Получение всех событий пользователя отсортированных по времени
        $events = $request->user()->events()->orderBy('date')->orderBy('lesson_number')->get();

        return $this->success('Fetched events successfully', $events);
    }

    public function destroy(Request $request, $id) : JsonResponse {
        $event = $request->user()->events()->find($id);

        if (!$event) {
            return $this->error("Event doesn't exists");
        }

        $event->delete();
        return response()->json([], 204);
    }

    public function update(Request $request, $id) : JsonResponse {
        $event = Event::find($id);

        if (!$event) {
            return error(message: 'Event not found');
        }

            // Проверяем, принадлежит ли событие текущему пользователю
        if ($event->user_id !== request()->user()->id) {
            return $this->error("Forbidden", 403);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'teacher' => 'nullable|string|max:255',
            'lesson_number' => 'nullable|integer|between:1,8',
            'date' => 'nullable|date'
        ]); 

        if ($request->has('title')){
            $event->title = $request->input('title');
        }
        if ($request->has('teacher')){
            $event->teacher = $request->input('teacher');
        }
        if ($request->has('lesson_number')){
            $event->title = $request->input('lesson_number');
        }
        if ($request->has('date')){
            $event->title = $request->input('date');
        }
        
        $event->save();
        
        return $this->success('Event updated successfully', $event);
    }

    public function getWeek(Request $request) : JsonResponse {
        $shift = (int)$request->query('shift', 0); // Получаем shift из query-параметров
        $startOfWeek = $this->getStartOfWeek($shift);
        $endOfWeek = $startOfWeek->copy()->endOfWeek(); // Начало и конец недели
    
        $events = $request->user()->events()
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date')
            ->orderBy('lesson_number') // Сортируем сначала по дате, а затем по номеру пары
            ->get();

            $daysMap = [
                0 => 'monday',
                1 => 'tuesday',
                2 => 'wednesday',
                3 => 'thursday',
                4 => 'friday',
                5 => 'saturday',
                6 => 'sunday'
            ];

            $week = [
                'monday'    => [],
                'tuesday'   => [],
                'wednesday' => [],
                'thursday'  => [],
                'friday'    => [],
                'saturday'  => [],
                'sunday'    => []
            ];
            
            foreach ($events as $event) {
                $dayOfWeek = (Carbon::parse($event->date)->dayOfWeek - 1 + 7) % 7; // Номер дня недели, где вс - 0, пн - 1...
                $dayKey = $daysMap[$dayOfWeek]; // Преобразуем числовой индекс в строковый ключ
                $week[$dayKey][] = $event;
            }
            return $this->success("Week fetched successfully", $week);
        }
            

    private function getStartOfWeek($shift = 0)
    {
        return Carbon::now()->startOfWeek()->addWeeks($shift);
    }
}
