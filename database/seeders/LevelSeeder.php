<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::create([
            "title" => "Principiante",
            "slug" => Str::slug("Principiante")
        ]);

        Level::create([
            "title" => "Intermedio",
            "slug" => Str::slug("Intermedio")
        ]);

        Level::create([
            "title" => "Avanzado",
            "slug" => Str::slug("Avanzado")
        ]);
    }
}
