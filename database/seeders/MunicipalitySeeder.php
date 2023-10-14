<?php

namespace Database\Seeders;

use App\Models\Municipality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ahuachapan
        Municipality::create([
            "name" => "Ahuachapán",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "Apaneca",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "Atiquizaya",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "Concepción de Ataco",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "El Refugio",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "Guaymango",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "Jujutla",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "San Francisco Menéndez",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "San Lorenzo",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "San Pedro Puxtla",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "Tacuba",
            "department_id" => 1,
        ]);
        Municipality::create([
            "name" => "Turín",
            "department_id" => 1,
        ]);

        // Sonsonate
        Municipality::create([
            "name" => "Acajutla",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Armenia",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Caluco",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Cuisnahuat",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Izalco",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Juayúa",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Nahuizalco",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Nahulingo",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Salcoatitán",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "San Antonio del Monte",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "San Julián",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Santa Catarina Masahuat",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Santa Isabel Ishuatán",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Santo Domingo Guzmán",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Sonsonate",
            "department_id" => 13,
        ]);
        Municipality::create([
            "name" => "Sonzacate",
            "department_id" => 13,
        ]);
    }
}
