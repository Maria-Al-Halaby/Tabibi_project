<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureLimit;
use App\Models\AiFeatureUsage;
use App\Services\AiFeatureLimitService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AiLimitController extends Controller
{
    public function __construct(private readonly AiFeatureLimitService $limits) {}

    public function index(Request $request)
    {
        $limits = $this->limits->all();
        $featureTypes = $limits->keys()->values();

        $usages = AiFeatureUsage::with('patient.user')
            ->when($request->filled('feature_type'), function ($query) use ($request) {
                $query->where('feature_type', $request->input('feature_type'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));

                $query->whereHas('patient.user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('period_start')
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('Super Admin.ai_limits.index', compact('limits', 'featureTypes', 'usages'));
    }

    public function updateLimit(Request $request, string $featureType)
    {
        $allowedFeatures = array_keys(config('ai_limits'));

        abort_unless(in_array($featureType, $allowedFeatures, true), 404);

        $data = $request->validate([
            'limit' => ['required', 'integer', 'min:0', 'max:1000000'],
            'period' => ['required', Rule::in(['day', 'week', 'month'])],
        ]);

        AiFeatureLimit::updateOrCreate(
            ['feature_type' => $featureType],
            [
                'limit' => $data['limit'],
                'period' => $data['period'],
            ]
        );

        return redirect()
            ->route('SuperAdmin.AiLimits.index')
            ->with('message', 'AI feature limit updated successfully.');
    }

    public function updateUsage(Request $request, AiFeatureUsage $usage)
    {
        $data = $request->validate([
            'used_count' => ['required', 'integer', 'min:0', 'max:1000000'],
        ]);

        $usage->update([
            'used_count' => $data['used_count'],
        ]);

        return redirect()
            ->route('SuperAdmin.AiLimits.index', $request->only(['feature_type', 'search', 'page']))
            ->with('message', 'User AI usage updated successfully.');
    }
}
