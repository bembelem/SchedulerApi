<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTemplateRequest;
use App\Http\Requests\UpdateTemplateRequest;
use App\Http\Services\TemplateService;
use App\Models\WeekTemplate;
use App\Traits\ApiResponses;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeekTemplateController extends Controller
{
    use ApiResponses;
    protected TemplateService $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function store(StoreTemplateRequest $request): JsonResponse
    {
        $template = $this->templateService->createTemplate($request->user(), $request->validated());
        return $this->success('Template successfully created', ['template' => $template], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $templates = $request->user()->weektemplates()->get()->keyBy('id');

        return $this->success('Templates fetched successfully', $templates);
    }

    public function update(UpdateTemplateRequest $request, $id): JsonResponse
    {
        $template = $this->templateService->updateTemplate($request->user(), $id, $request->validated());

        return $this->success('Template successfully updated', $template);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $this->templateService->deleteTemplate($request->user(), $id);
        return $this->noContent();

    }

    public function apply(Request $request, $id): JsonResponse
    {
        $request->validate([
            'shift' => 'required|integer'
        ]);

        $weekShift = (int)$request->input('shift');
        $shift = (int)$request->shift;
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($shift);
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        $newWeek = $this->templateService->applyTemplate($request->user(), $id,  $startOfWeek, $endOfWeek, $shift);

        return $this->success('Template applied successfully', ['week'=>$newWeek]);
    }

    protected function transformEmptyFieldsToObjects($templateData)
    {
        // Преобразуем пустые массивы в пустые объекты
        foreach ($templateData['timetable'] as $day => $events) {
            if (empty($events)) {
            // Преобразуем в пустой объект
                $templateData['timetable'][$day] = new \stdClass();
        }
    }
        return $templateData;
    }

    private function getStartOfWeek($shift = 0)
    {
        return Carbon::now()->startOfWeek()->addWeeks($shift);
    }

} 