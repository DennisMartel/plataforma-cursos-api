<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Image;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Course::factory(40)->create()->each(function ($course) {
      Image::factory(1)->create([
        "imageable_id" => $course->id,
        "imageable_type" => Course::class,
      ]);
      Section::factory(3)->create([
        "course_id" => $course->id
      ])->each(function ($section) {
        Lesson::factory(4)->create([
          "section_id" => $section->id
        ]);
      });
    });
  }
}
