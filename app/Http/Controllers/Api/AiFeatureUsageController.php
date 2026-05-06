<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiFeatureUsageController extends Controller
{
    public function useFeature(Request $request)
    {
        $data = $request->validate([
            //'patient_id' => ['required', 'exists:patients,id'],
            'feature_type' => ['required', 'string'],
        ]);

        $patientId = auth()->user()->patient->id;
        $featureType = $data['feature_type'];

        $featureConfig = config("ai_limits.$featureType");

        if (!$featureConfig) {
            return response()->json([
                'message' => 'Invalid feature type.',
            ], 422);
        }

        [$periodStart, $periodEnd] = $this->getPeriodRange($featureConfig['period']);

        $limit = $featureConfig['limit'];

        $usage = DB::transaction(function () use (
            $patientId,
            $featureType,
            $periodStart,
            $periodEnd,
            $limit
        ) {
            $usage = AiFeatureUsage::where('patient_id', $patientId)
                ->where('feature_type', $featureType)
                ->where('period_start', $periodStart)
                ->where('period_end', $periodEnd)
                ->lockForUpdate()
                ->first();

            if (!$usage) {
                $usage = AiFeatureUsage::create([
                    'patient_id' => $patientId,
                    'feature_type' => $featureType,
                    'used_count' => 0,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                ]);
            }

            if ($usage->used_count >= $limit) {
                return null;
            }

            $usage->increment('used_count');

            return $usage->fresh();
        });

        if (!$usage) {
            return response()->json([
                'message' => 'You have reached the limit for this AI feature.',
                'status' => 404 ,
                'feature_type' => $featureType,
                'limit' => $limit,
                'used' => $limit,
                'remaining' => 0,
            ], 429);
        }

        return response()->json([
            'message' => 'AI feature usage recorded successfully.',
            'stauts' => 200 ,
            'feature_type' => $featureType,
            'limit' => $limit,
            'used' => $usage->used_count,
            'remaining' => max(0, $limit - $usage->used_count),
            'period_start' => $usage->period_start,
            'period_end' => $usage->period_end,
        ]);
    }

    public function remaining(Request $request)
    {
       /*  $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
        ]);
 */
        $patientId = auth()->user()->patient->id;
        $result = [];

        foreach (config('ai_limits') as $featureType => $featureConfig) {
            [$periodStart, $periodEnd] = $this->getPeriodRange($featureConfig['period']);

            $usage = AiFeatureUsage::where('patient_id', $patientId)
                ->where('feature_type', $featureType)
                ->where('period_start', $periodStart)
                ->where('period_end', $periodEnd)
                ->first();

            $used = $usage?->used_count ?? 0;
            $limit = $featureConfig['limit'];

            $result[$featureType] = [
                'limit' => $limit,
                'used' => $used,
                'remaining' => max(0, $limit - $used),
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
            ];
        }

        return response()->json(["message" => "remaining of use" ,
        "status" => 200 , 
        "remaining" => $result]);
    }

    private function getPeriodRange(string $period): array
    {
        return match ($period) {
            'day' => [
                now()->startOfDay(),
                now()->endOfDay(),
            ],

            'month' => [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ],

            default => [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ],
        };
    }
}
