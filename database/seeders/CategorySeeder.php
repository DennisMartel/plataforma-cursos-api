<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            "title" => "Desarrollo web",
            "slug" => Str::slug("Desarrollo web"),
            "icon" => "<i class='fa fa-laptop' aria-hidden='true'></i>",
            "image" => "https://dummyimage.com/100&text=image"
        ]);

        Category::create([
            "title" => "Desarrollo móvil",
            "slug" => Str::slug("Desarrollo móvil"),
            "icon" => "<i class='fa fa-mobile' aria-hidden='true'></i>",
            "image" => "https://dummyimage.com/100&text=image"
        ]);
        
        Category::create([
            "title" => "Desarrollo de videojuegos",
            "slug" => Str::slug("Desarrollo de videojuegos"),
            "icon" => "<i class='fa fa-gamepad' aria-hidden='true'></i>",
            "image" => "https://dummyimage.com/100&text=image"
        ]);

        Category::create([
            "title" => "Bases de datos",
            "slug" => Str::slug("Bases de datos"),
            "icon" => "<i class='fa fa-database' aria-hidden='true'></i>",
            "image" => "https://dummyimage.com/100&text=image"
        ]);

        Category::create([
            "title" => "Lenguajes de programación",
            "slug" => Str::slug("Lenguajes de programación"),
            "icon" => "<i class='fa fa-code' aria-hidden='true'></i>",
            "image" => "https://dummyimage.com/100&text=image"
        ]);
    }
}
