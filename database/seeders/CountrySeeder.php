<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::create([
            "country" => "El Salvador",
            "country_code" => "+503",
            "iso_code" => "SV",
            "alpha_three_code" => "SLV",
            "flag_icon" => asset("flags_icon/El-salvador.png")
        ]);

        Country::create([
            "country" => "Colombia",
            "country_code" => "+57",
            "iso_code" => "CO",
            "alpha_three_code" => "COL",
            "flag_icon" => asset("flags_icon/Colombia.png")
        ]);
    }
}
