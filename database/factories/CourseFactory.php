<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $title = $this->faker->sentence();

    return [
      "title" => $title,
      "subtitle" => $this->faker->sentence(10),
      "slug" => Str::slug($title),
      "description" => $this->faker->paragraph(),
      "price" => $this->faker->randomFloat(2, 9, 30),
      "status" => Course::APPROVED,
      "user_id" => User::all()->random()->id,
      "category_id" => Category::all()->random()->id,
      "level_id" => Level::all()->random()->id,
    ];
  }
}
