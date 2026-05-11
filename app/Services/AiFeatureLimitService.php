<?php

namespace App\Services;

use App\Models\AiFeatureLimit;
use Illuminate\Support\Collection;

class AiFeatureLimitService
{
    public function all(): Collection
    {
        $storedLimits = AiFeatureLimit::query()
            ->get()
            ->keyBy('feature_type');

        return collect(config('ai_limits'))
            ->map(function (array $config, string $featureType) use ($storedLimits) {
                $stored = $storedLimits->get($featureType);

                return [
                    'feature_type' => $featureType,
                    'limit' => (int) ($stored?->limit ?? $config['limit']),
                    'period' => $stored?->period ?? $config['period'],
                    'default_limit' => (int) $config['limit'],
                    'default_period' => $config['period'],
                    'is_customized' => (bool) $stored,
                ];
            });
    }

    public function get(string $featureType): ?array
    {
        return $this->all()->get($featureType);
    }
}
