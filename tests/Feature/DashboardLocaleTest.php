<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_switch_dashboard_language_to_arabic(): void
    {
        $this->post(route('language.switch', 'ar'))
            ->assertRedirect()
            ->assertSessionHas('locale', 'ar');
    }

    public function test_dashboard_uses_arabic_locale_and_rtl_direction(): void
    {
        Role::findOrCreate('super admin', 'web');

        $superAdmin = User::factory()->create([
            'name' => 'Platform',
            'last_name' => 'Owner',
        ]);
        $superAdmin->assignRole('super admin');

        $this->withSession(['locale' => 'ar'])
            ->actingAs($superAdmin)
            ->get(route('SuperAdmin.Detials.index'))
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('لوحة المدير العام')
            ->assertSee('تسجيل الخروج');
    }

    public function test_auth_screens_use_arabic_locale_before_login(): void
    {
        $this->withSession(['locale' => 'ar'])
            ->get(route('login'))
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('مرحباً بعودتك')
            ->assertSee('مبدل اللغة');

        $this->withSession(['locale' => 'ar'])
            ->get(route('doctor.login'))
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('دخول الطبيب');

        $this->withSession(['locale' => 'ar'])
            ->get(route('secretary.login'))
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('دخول السكرتارية');
    }

    public function test_doctor_type_labels_are_localized_without_changing_form_values(): void
    {
        Role::findOrCreate('super admin', 'web');

        $superAdmin = User::factory()->create([
            'name' => 'Platform',
            'last_name' => 'Owner',
        ]);
        $superAdmin->assignRole('super admin');

        $this->withSession(['locale' => 'ar'])
            ->actingAs($superAdmin)
            ->get(route('SuperAdmin.doctor.create'))
            ->assertOk()
            ->assertSee('الأشعة')
            ->assertSee('المخبر')
            ->assertSee('value="radiology"', false)
            ->assertSee('value="lab"', false);
    }
}
