<?php

namespace Tests\Feature;

use App\Models\EventLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperAdminEventLogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_event_logs(): void
    {
        Role::findOrCreate('super admin', 'web');

        $superAdmin = User::factory()->create([
            'name' => 'Platform',
            'last_name' => 'Owner',
        ]);
        $superAdmin->assignRole('super admin');

        EventLog::create([
            'user_id' => $superAdmin->id,
            'user_name' => 'Platform Owner',
            'user_role' => 'super admin',
            'table_name' => 'specializations',
            'model_type' => 'App\\Models\\Specialization',
            'model_id' => '7',
            'status' => 'add',
            'message' => 'Platform Owner (super admin) added record #7 in specializations.',
            'parameters' => ['name' => 'Cardiology'],
        ]);

        $this->actingAs($superAdmin)
            ->get(route('SuperAdmin.EventLogs.index'))
            ->assertOk()
            ->assertSee('Event Logs')
            ->assertSee('specializations')
            ->assertSee('Platform Owner')
            ->assertSee('Cardiology');
    }
}
