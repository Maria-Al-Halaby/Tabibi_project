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
            $user = User::create([
            "name" => "Super Admin" , 
            "email" => "superAdmin@gmail.com" , 
            "password" => Hash::make("password")
        ]); 

        $user->assignRole("super admin"); 

        $user = User::create([
            "name" => "Admin" , 
            "email" => "admin@gmail.com" , 
            "password" => Hash::make("password")
        ]);

        $user->assignRole("admin");

        
        ClinicCenter::create([
            "user_id" => $user->id , 
            "name" => $user->name, 
            "address" => "Damascus/Syria"
        ]);
        
    } 
}
