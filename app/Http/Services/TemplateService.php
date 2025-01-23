<?php

namespace App\Http\Services;

use App\Exceptions\DuplicateEventException;
use App\Exceptions\NotAuthorizedException;
use App\Models\Event;
use App\Models\User;
use App\Models\WeekTemplate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TemplateService
{
    public function createTemplate(User $user, array $data): WeekTemplate
    {
        $template = $user->weektemplates()->create($data);
        return $template;
    }

    public function updateTemplate(User $user, int $templateId, array $data): WeekTemplate 
    {
        $template = WeekTemplate::findOrFail($templateId);
        if ($user->id !== $template->user_id) {
            throw new NotAuthorizedException; 
        }

        $template->update($data);
        return $template;
    }

    public function deleteTemplate(User $user, int $templateId): void 
    {
        $template = WeekTemplate::findOrFail($templateId);
        if ($user->id !== $template->user_id) {
            throw new NotAuthorizedException; 
        }

        $template->delete();


    }
    public function applyTemplate(User $user, int $templateId, Carbon $startOfWeek, Carbon $endOfWeek, int $shift)
    { 
        $template = WeekTemplate::findOrFail($templateId);

        if ($user->id !== $template->user_id) {
            throw new NotAuthorizedException; 
        }

        $eventService = new EventService;

        DB::beginTransaction();
        $eventService->deleteWeekEvents($user, $startOfWeek, $endOfWeek);
        $dayShift = 0;

        foreach($template['timetable'] as $day => $events){
            foreach ($events as $eventData){
                $eventDate = $startOfWeek->copy()->addDays($dayShift);
                $eventData = array_merge((array)$eventData, ['date' => $eventDate->format('Y-m-d')]);

                $eventService->createEvent($user, $eventData);
            }
            $dayShift++;
        }

        DB::commit();

        return $eventService->getWeekEvents($user, $shift);
    }

    public function deleteWeekEvents(User $user, int $shift)
    {
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($shift);
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        $events = $user->events()
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->delete();
        
    }


    private function initializeWeekStructure($events)
    {
        $week = (object)[
            'monday' => (object)[],
            'tuesday' => (object)[],
            'wednesday' => (object)[],
            'thursday' => (object)[],
            'friday' => (object)[],
            'saturday' => (object)[],
            'sunday' => (object)[],
        ];

        foreach ($events as $event) {
            $day = $event->day;
            $week->$day->{$event->order} = $event;
        }

        return $week;
    }
}