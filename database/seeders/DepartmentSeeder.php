<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            "name" => "Ahuachapán",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "Cabañas",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "Chalatenango",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "Cuscatlán",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "La Libertad",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "La Paz",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "La Unión",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "Morazán",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "San Miguel",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "San Salvador",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "San Vicente",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "Santa Ana",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "Sonsonate",
            "country_id" => 1
        ]);
        Department::create([
            "name" => "Usulután",
            "country_id" => 1
        ]);
    }
}
