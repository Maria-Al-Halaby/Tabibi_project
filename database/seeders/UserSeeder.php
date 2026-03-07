<?php

namespace Database\Seeders;

use App\Models\ClinicCenter;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ["email" => "superAdmin@gmail.com"],
            [
                "name" => "Super Admin",
                "password" => Hash::make("password")
            ]
        );

        if (!$superAdmin->hasRole("super admin")) {
            $superAdmin->assignRole("super admin");
        }

        $admin = User::firstOrCreate(
            ["email" => "admin@gmail.com"],
            [
                "name" => "Admin",
                "password" => Hash::make("password")
            ]
        );

        if (!$admin->hasRole("admin")) {
            $admin->assignRole("admin");
        }

        ClinicCenter::firstOrCreate(
            ["user_id" => $admin->id],
            [
                "name" => "Admin Center",
                "address" => "Damascus/Syria"
            ]
        );
        
    } 
}
