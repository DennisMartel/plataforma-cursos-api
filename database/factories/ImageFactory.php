<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date =  date("Y") . "/" . date("m") . "/" . date("d");
        return [
            "url" => "courses/{$date}/".$this->faker->image("public/storage/courses/{$date}", 640, 480, null, false),
        ];
    }
}
