<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


 
    public function run(): void
    {

    $roles = [
    ["name" => "super admin", "guard_name" => "web"] , 
    ["name" => "admin" , "guard_name" => "web"], 
    ["name" => "secretary" , "guard_name" => "web"], 
    ["name" => "doctor" , "guard_name" => "web"],
    ["name" => "patient" , "guard_name" => "web"],
    ["name" => "radiologist", "guard_name" => "web"],
    ["name" => "lab technician", "guard_name" => "web"],
    ["name" => "pharmacist", "guard_name" => "web"]
    ];
        foreach($roles as $role)
        {
            Role::firstOrCreate([
                "name" => $role["name"] ,
                "guard_name" => $role["guard_name"]
            ]);
        }
    }
}
