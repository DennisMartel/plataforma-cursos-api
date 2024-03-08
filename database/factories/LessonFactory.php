<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $urls = [
      "https://youtu.be/FKsoF3htA6s?si=GocC0_5ZON2oUX3w",
      "https://youtu.be/ECDgNM9xEYQ?si=tkxqWo-1zN7MSNM7",
      "https://youtu.be/kTe1XZfYq9M?si=_37fAfaszrMhZGfH",
      "https://youtu.be/WAJKSPJnIKo?si=nNcHG8m_aITgV_Vv",
      "https://youtu.be/j7vXHVtHJ1E?si=pBYSN9F6vAUINmAq"
    ];

    return [
      "title" => $this->faker->sentence(),
      "url" => $this->faker->randomElement($urls),
    ];
  }
}
