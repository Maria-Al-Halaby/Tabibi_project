<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*   User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */

        /* اولا يتم تنفيذ ال RoleSeeder ثم بعد الخاص بالمستخدمين  */
        //$this->call(RolesSeeder::class);

        //$this->call(UserSeeder::class);

        /* هذا الخاص بالمعلومات التي تعرض بال home screen سيتم التعديل لاحقا ليتم ادخاله من ال main dashboard */
        //$this->call(PromotSeeder::class);
    }
}
