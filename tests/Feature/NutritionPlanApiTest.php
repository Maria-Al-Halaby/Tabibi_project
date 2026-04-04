<?php

namespace Tests\Feature;

use App\Models\NutritionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NutritionPlanApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_an_ai_generated_nutrition_plan(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'request' => [
                'name' => 'Ahmed',
                'age' => 30,
                'gender' => 'male',
                'height' => 175.5,
                'weight' => 80.0,
                'goal' => 'weight_loss',
            ],
            'plan' => [
                'summary_ar' => 'خطة غذائية علاجية مخصصة لمدة سبعة أيام.',
                'diet_type_applied' => 'Mediterranean diabetic-friendly',
                'daily_calories_target' => 1800,
                'daily_water_liters' => 2.5,
                'daily_macros_summary' => [
                    'protein' => '130g',
                    'carbs' => '170g',
                    'fats' => '60g',
                ],
                'week_plan' => [
                    'Saturday' => [
                        'breakfast' => [
                            'meal' => 'بيض مسلوق',
                            'calories' => 320,
                            'protein_g' => 22,
                            'carbs_g' => 18,
                            'fats_g' => 14,
                            'ingredients' => ['بيض', 'خبز قمح كامل'],
                            'preparation_tip' => 'اسلق البيض وقدمه مع الخبز.',
                        ],
                        'daily_advice' => 'ابدأ اليوم بالماء.',
                    ],
                    'Sunday' => [
                        'breakfast' => [
                            'meal' => 'لبنة وخيار',
                            'calories' => 280,
                            'protein_g' => 14,
                            'carbs_g' => 15,
                            'fats_g' => 12,
                            'ingredients' => ['لبنة', 'خيار'],
                            'preparation_tip' => 'قدمها باردة.',
                        ],
                        'daily_advice' => 'قلل الملح.',
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/api/nutrition-plans', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.item.request.name', 'Ahmed')
            ->assertJsonPath('data.item.plan.summary_ar', $payload['plan']['summary_ar'])
            ->assertJsonPath('data.item.plan.daily_macros_summary.protein', '130g')
            ->assertJsonPath('data.item.plan.week_plan.Saturday.breakfast.meal', 'بيض مسلوق');

        $this->assertDatabaseHas('nutrition_plans', [
            'user_id' => $user->id,
            'summary' => $payload['plan']['summary_ar'],
            'diet_type' => $payload['plan']['diet_type_applied'],
            'daily_calories_target' => 1800,
        ]);

        $plan = NutritionPlan::query()->firstOrFail();

        $this->assertEquals($payload['request'], $plan->generation_inputs);
        $this->assertSame($payload['plan']['daily_macros_summary'], $plan->macros);
        $this->assertSame($payload['plan']['week_plan']['Saturday'], $plan->saturday_plan);
        $this->assertSame($payload['plan']['week_plan']['Sunday'], $plan->sunday_plan);
    }

    public function test_it_lists_only_the_authenticated_users_plans_with_pagination_and_compact_plan_data(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        NutritionPlan::create([
            'user_id' => $user->id,
            'diet_type' => 'Balanced',
            'summary' => 'This is a short nutrition plan preview.',
            'daily_calories_target' => 1600,
            'daily_water_liters' => 2.2,
            'macros' => ['protein' => '120g', 'carbs' => '140g', 'fats' => '50g'],
            'generation_inputs' => ['name' => 'First User'],
            'week_plan' => ['Saturday' => ['daily_advice' => 'اشرب الماء']],
        ]);

        NutritionPlan::create([
            'user_id' => $otherUser->id,
            'diet_type' => 'Hidden',
            'summary' => 'This plan should remain hidden.',
            'daily_calories_target' => 2200,
            'generation_inputs' => ['name' => 'Second User'],
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/nutrition-plans');

        $response
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.meta.page', 1)
            ->assertJsonPath('data.meta.limit', 10)
            ->assertJsonPath('data.meta.total', 1)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', '1')
            ->assertJsonPath('data.items.0.request.name', 'First User')
            ->assertJsonPath('data.items.0.plan.diet_type_applied', 'Balanced')
            ->assertJsonPath('data.items.0.plan.daily_macros_summary.protein', '120g');

        $this->assertSame([], $response->json('data.items.0.plan.week_plan'));
    }

    public function test_it_fetches_a_single_plan_by_id_for_its_owner(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $plan = NutritionPlan::create([
            'user_id' => $user->id,
            'diet_type' => 'Clinical',
            'summary' => 'Full plan details.',
            'daily_calories_target' => 1900,
            'daily_water_liters' => 2.7,
            'macros' => [
                'protein' => '120g',
                'carbs' => '180g',
                'fats' => '55g',
            ],
            'generation_inputs' => [
                'name' => 'Owner User',
                'goal' => 'muscle_gain',
            ],
            'week_plan' => [
                'Saturday' => [
                    'lunch' => ['meal' => 'دجاج مشوي'],
                ],
            ],
        ]);

        Sanctum::actingAs($user);

        $this->getJson("/api/nutrition-plans/{$plan->id}")
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.id', (string) $plan->id)
            ->assertJsonPath('data.request.name', 'Owner User')
            ->assertJsonPath('data.plan.summary_ar', 'Full plan details.')
            ->assertJsonPath('data.plan.week_plan.Saturday.lunch.meal', 'دجاج مشوي');

        Sanctum::actingAs($otherUser);

        $this->getJson("/api/nutrition-plans/{$plan->id}")
            ->assertNotFound()
            ->assertJsonPath('status', false);
    }
}
