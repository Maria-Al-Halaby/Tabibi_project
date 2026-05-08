<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NutritionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use stdClass;

class NutritionPlanController extends Controller
{
    private const DAY_COLUMN_MAP = [
        'Saturday' => 'saturday_plan',
        'Sunday' => 'sunday_plan',
        'Monday' => 'monday_plan',
        'Tuesday' => 'tuesday_plan',
        'Wednesday' => 'wednesday_plan',
        'Thursday' => 'thursday_plan',
        'Friday' => 'friday_plan',
    ];

    public function index(Request $request)
    {
        $query = NutritionPlan::query()
            ->where('user_id', $request->user()->id)
            ->latest();

        $page = max((int) $request->integer('page', 1), 1);
        $limit = min(max((int) $request->integer('limit', 10), 1), 50);

        $paginated = $query->paginate($limit, ['*'], 'page', $page);

        $items = collect($paginated->items())
            ->map(fn (NutritionPlan $plan) => $this->transformHistoryItem($plan, true))
            ->values();

        return response()->json([
            'message' => 'Nutrition plans fetched successfully',
            'status' => true,
            'data' => [
                'items' => $items,
                'meta' => [
                    'page' => $paginated->currentPage(),
                    'limit' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ],
            ],
        ], 200);
    }

    public function store(Request $request)
    {
        $payload = $this->normalizeIncomingPayload($request->all());

        $validator = Validator::make($payload, [
            'request' => 'nullable|array',
            'plan' => 'required|array',
            'plan.summary' => 'nullable|string',
            'plan.summary_ar' => 'nullable|string',
            'plan.diet_type_applied' => 'nullable|string|max:255',
            'plan.daily_calories_target' => 'nullable|numeric|min:0',
            'plan.daily_water_liters' => 'nullable|numeric|min:0',
            'plan.daily_macros_summary' => 'nullable|array',
            'plan.daily_macros_summary.protein' => 'nullable',
            'plan.daily_macros_summary.carbs' => 'nullable',
            'plan.daily_macros_summary.fats' => 'nullable',
            'plan.daily_macros_summary.protein_g' => 'nullable|numeric|min:0',
            'plan.daily_macros_summary.carbs_g' => 'nullable|numeric|min:0',
            'plan.daily_macros_summary.fats_g' => 'nullable|numeric|min:0',
            'plan.week_plan' => 'nullable|array',
        ]);

        $validator->after(function ($validator) use ($payload) {
            $hasWeekPlan = is_array(data_get($payload, 'plan.week_plan'))
                && count(data_get($payload, 'plan.week_plan')) > 0;

            if (! $hasWeekPlan) {
                $validator->errors()->add('week_plan', 'A week plan or at least one daily plan is required.');
            }
        });

        $data = $validator->validate();
        $user = auth()->user();
        $planPayload = $data['plan'];
        $weekPlan = $planPayload['week_plan'] ?? null;
        $summary = $planPayload['summary'] ?? $planPayload['summary_ar'] ?? null;

        $plan = NutritionPlan::create([
            'user_id' => $user->id,
            'diet_type' => $planPayload['diet_type_applied'] ?? null,
            'macros' => $this->normalizeMacrosForStorage($planPayload['daily_macros_summary'] ?? null),
            'goal_note' => $summary,
            'generation_inputs' => $data['request'] ?? null,
            'summary' => $summary,
            'daily_calories_target' => isset($planPayload['daily_calories_target'])
                ? (int) round((float) $planPayload['daily_calories_target'])
                : null,
            'daily_water_liters' => isset($planPayload['daily_water_liters'])
                ? (float) $planPayload['daily_water_liters']
                : null,
            'week_plan' => $weekPlan,
            'saturday_plan' => Arr::get($weekPlan, 'Saturday'),
            'sunday_plan' => Arr::get($weekPlan, 'Sunday'),
            'monday_plan' => Arr::get($weekPlan, 'Monday'),
            'tuesday_plan' => Arr::get($weekPlan, 'Tuesday'),
            'wednesday_plan' => Arr::get($weekPlan, 'Wednesday'),
            'thursday_plan' => Arr::get($weekPlan, 'Thursday'),
            'friday_plan' => Arr::get($weekPlan, 'Friday'),
        ]);

        return response()->json([
            'message' => 'Nutrition plan saved successfully',
            'status' => true,
            'data' => [
                'nutrition_plan_id' => $plan->id,
                'item' => $this->transformHistoryItem($plan),
            ]
        ], 201);
    }

    public function show(Request $request, int $id)
    {
        $plan = NutritionPlan::query()
            ->where('user_id', $request->user()->id)
            ->whereKey($id)
            ->first();

        if (! $plan) {
            return response()->json([
                'message' => 'Nutrition plan not found',
                'status' => false,
            ], 404);
        }

        return response()->json([
            'message' => 'Nutrition plan fetched successfully',
            'status' => true,
            'data' => $this->transformHistoryItem($plan),
        ], 200);
    }

    public function latest(Request $request)
    {
        $plan = NutritionPlan::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->first();

        if (! $plan) {
            return response()->json([
                'message' => 'No nutrition plan found',
                'status' => false,
            ], 404);
        }

        return response()->json([
            'message' => 'Nutrition plan fetched successfully',
            'status' => true,
            'data' => $this->transformHistoryItem($plan),
        ], 200);
    }

    private function normalizeIncomingPayload(array $input): array
    {
        $requestPayload = is_array($input['request'] ?? null)
            ? $input['request']
            : (is_array($input['generation_inputs'] ?? null) ? $input['generation_inputs'] : null);

        $planPayload = is_array($input['plan'] ?? null) ? $input['plan'] : [];

        if ($planPayload === []) {
            $planPayload = [
                'summary' => $input['summary'] ?? null,
                'summary_ar' => $input['summary_ar'] ?? null,
                'diet_type_applied' => $input['diet_type_applied'] ?? $input['diet_type'] ?? null,
                'daily_calories_target' => $input['daily_calories_target'] ?? null,
                'daily_water_liters' => $input['daily_water_liters'] ?? null,
                'daily_macros_summary' => $input['daily_macros_summary'] ?? $input['macros'] ?? null,
                'week_plan' => $this->buildWeekPlanPayload($input),
            ];
        } else {
            $planPayload['summary'] = $planPayload['summary'] ?? $input['summary'] ?? null;
            $planPayload['summary_ar'] = $planPayload['summary_ar'] ?? $input['summary_ar'] ?? null;
            $planPayload['week_plan'] = is_array($planPayload['week_plan'] ?? null)
                ? $planPayload['week_plan']
                : $this->buildWeekPlanPayload($input);
            $planPayload['daily_macros_summary'] = $planPayload['daily_macros_summary']
                ?? $input['daily_macros_summary']
                ?? $input['macros']
                ?? null;
            $planPayload['diet_type_applied'] = $planPayload['diet_type_applied']
                ?? $input['diet_type_applied']
                ?? $input['diet_type']
                ?? null;
            $planPayload['daily_calories_target'] = $planPayload['daily_calories_target']
                ?? $input['daily_calories_target']
                ?? null;
            $planPayload['daily_water_liters'] = $planPayload['daily_water_liters']
                ?? $input['daily_water_liters']
                ?? null;
        }

        return [
            'request' => $requestPayload,
            'plan' => $planPayload,
        ];
    }

    private function buildWeekPlanPayload(array $data): ?array
    {
        $weekPlan = is_array($data['week_plan'] ?? null) ? $data['week_plan'] : [];

        foreach (self::DAY_COLUMN_MAP as $day => $column) {
            if (array_key_exists($column, $data)) {
                $weekPlan[$day] = $data[$column];
            }
        }

        return $weekPlan === [] ? null : $weekPlan;
    }

    private function resolveWeekPlan(NutritionPlan $plan): array
    {
        if (is_array($plan->week_plan) && $plan->week_plan !== []) {
            return $plan->week_plan;
        }

        $weekPlan = [];

        foreach (self::DAY_COLUMN_MAP as $day => $column) {
            if (! is_null($plan->{$column})) {
                $weekPlan[$day] = $plan->{$column};
            }
        }

        return $weekPlan;
    }

    private function transformHistoryItem(NutritionPlan $plan, bool $compactPlan = false): array
    {
        return [
            'id' => (string) $plan->id,
            'created_at' => optional($plan->created_at)?->toISOString(),
            'request' => $plan->generation_inputs ?? (object) [],
            'plan' => $this->transformPlanPayload($plan, $compactPlan),
        ];
    }

    private function transformPlanPayload(NutritionPlan $plan, bool $compact = false): array
    {
        $summary = $plan->summary ?? $plan->goal_note;
        $weekPlan = $compact ? [] : $this->resolveWeekPlan($plan);

        return [
            'summary' => $summary,
            'summary_ar' => $summary,
            'diet_type_applied' => $plan->diet_type,
            'daily_calories_target' => $plan->daily_calories_target,
            'daily_water_liters' => $plan->daily_water_liters,
            'daily_macros_summary' => $this->normalizeMacrosForResponse($plan->macros),
            'week_plan' => $weekPlan === [] ? (object) [] : $weekPlan,
        ];
    }

    private function normalizeMacrosForStorage(?array $macros): ?array
    {
        if (! is_array($macros)) {
            return null;
        }

        return [
            'protein' => $this->normalizeMacroValue(
                $macros['protein'] ?? $macros['protein_g'] ?? null
            ),
            'carbs' => $this->normalizeMacroValue(
                $macros['carbs'] ?? $macros['carbs_g'] ?? null
            ),
            'fats' => $this->normalizeMacroValue(
                $macros['fats'] ?? $macros['fats_g'] ?? null
            ),
        ];
    }

    private function normalizeMacrosForResponse(mixed $macros): array
    {
        $macros = is_array($macros) ? $macros : [];

        return [
            'protein' => $this->normalizeMacroValue(
                $macros['protein'] ?? $macros['protein_g'] ?? null
            ) ?? '',
            'carbs' => $this->normalizeMacroValue(
                $macros['carbs'] ?? $macros['carbs_g'] ?? null
            ) ?? '',
            'fats' => $this->normalizeMacroValue(
                $macros['fats'] ?? $macros['fats_g'] ?? null
            ) ?? '',
        ];
    }

    private function normalizeMacroValue(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $formatted = (float) $value == (int) $value
                ? (string) (int) $value
                : rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');

            return $formatted . 'g';
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return Str::endsWith(Str::lower($value), 'g') ? $value : $value . 'g';
    }
}
