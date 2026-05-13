<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureUsage;
use App\Models\User;
use App\Services\AiFeatureLimitService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiFeatureController extends Controller
{
    private const ALLOWED_FEATURES = [
        'generate-diet-plan',
        'analyze-xray',
        'get-symptoms',
        'diagnose',
    ];

    private const USAGE_FEATURE_MAP = [
        'generate-diet-plan' => 'program_diet',
        'analyze-xray' => 'xray_analysis',
        'get-symptoms' => 'diagnosis',
    ];

    public function __construct(private readonly AiFeatureLimitService $limits) {}

    public function proxy(Request $request, string $feature)
    {
        $timeoutSeconds = max(1, (int) config('services.n8n.timeout', 180));
        set_time_limit($timeoutSeconds + 10);

        if (! in_array($feature, self::ALLOWED_FEATURES, true)) {
            return response()->json([
                'message' => 'Invalid AI feature.',
            ], 400);
        }

        $user = $request->user();
        $usageFeature = self::USAGE_FEATURE_MAP[$feature] ?? null;

        if ($usageFeature && $this->hasReachedAiLimit($user, $usageFeature)) {
            return response()->json([
                'message' => 'You have reached the limit for this AI feature.',
            ], 429);
        }

        $baseUrl = rtrim((string) config('services.n8n.base_url'), '/');
        $secretKey = (string) config('services.n8n.secret_key');

        if ($baseUrl === '' || $secretKey === '') {
            Log::error('n8n proxy configuration is missing.', [
                'feature' => $feature,
                'user_id' => $user?->id,
            ]);

            return response()->json([
                'message' => 'Unable to process this AI request right now.',
            ], 500);
        }

        try {
            $response = Http::withHeaders([
                'TABIBY-AI-N8N' => $secretKey,
            ])
                ->timeout($timeoutSeconds)
                ->connectTimeout(10)
                ->post("{$baseUrl}/webhook/{$feature}", $request->all());

            if (! $response->successful() || $response->status() !== 200) {
                Log::error('n8n proxy request failed.', [
                    'feature' => $feature,
                    'user_id' => $user?->id,
                    'status' => $response->status(),
                ]);

                return response()->json([
                    'message' => 'Unable to process this AI request right now.',
                ], 500);
            }

            if ($usageFeature) {
                $this->recordAiUsage($user, $usageFeature);
            }

            return response()->json($response->json(), 200);
        } catch (ConnectionException $exception) {
            Log::error('n8n proxy connection failed.', [
                'feature' => $feature,
                'user_id' => $user?->id,
                'exception' => $exception::class,
            ]);
        } catch (\Throwable $exception) {
            Log::error('n8n proxy failed.', [
                'feature' => $feature,
                'user_id' => $user?->id,
                'exception' => $exception::class,
            ]);
        }

        return response()->json([
            'message' => 'Unable to process this AI request right now.',
        ], 500);
    }

    private function hasReachedAiLimit(User $user, string $featureType): bool
    {
        $patientId = $user->patient?->id;
        $featureConfig = $this->limits->get($featureType);

        if (! $patientId || ! $featureConfig) {
            return true;
        }

        [$periodStart, $periodEnd] = $this->getPeriodRange($featureConfig['period']);

        $usedCount = AiFeatureUsage::where('patient_id', $patientId)
            ->where('feature_type', $featureType)
            ->where('period_start', $periodStart)
            ->where('period_end', $periodEnd)
            ->value('used_count') ?? 0;

        return $usedCount >= $featureConfig['limit'];
    }

    private function recordAiUsage(User $user, string $featureType): void
    {
        $patientId = $user->patient?->id;
        $featureConfig = $this->limits->get($featureType);

        if (! $patientId || ! $featureConfig) {
            return;
        }

        [$periodStart, $periodEnd] = $this->getPeriodRange($featureConfig['period']);

        DB::transaction(function () use ($patientId, $featureType, $periodStart, $periodEnd) {
            $usage = AiFeatureUsage::where('patient_id', $patientId)
                ->where('feature_type', $featureType)
                ->where('period_start', $periodStart)
                ->where('period_end', $periodEnd)
                ->lockForUpdate()
                ->first();

            if (! $usage) {
                $usage = AiFeatureUsage::create([
                    'patient_id' => $patientId,
                    'feature_type' => $featureType,
                    'used_count' => 0,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                ]);
            }

            $usage->increment('used_count');
        });
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
