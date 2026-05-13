<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FCMTokenApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_fcm_token(): void
    {
        $user = User::factory()->create([
            'fcm_token' => null,
        ]);

        Sanctum::actingAs($user);

        $this->putJson('/api/fcm-token', [
            'fcm_token' => 'firebase-device-token-123',
        ])
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.fcm_token', 'firebase-device-token-123');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'fcm_token' => 'firebase-device-token-123',
        ]);
    }
}
