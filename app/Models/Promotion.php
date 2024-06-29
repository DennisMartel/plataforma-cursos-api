<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
  use HasFactory;

  protected $fillable = [
    "title", "discount_percentage", "start_date", "end_date"
  ];

  public function courses()
  {
    return $this->belongsToMany(Course::class, "course_promotion");
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class, "category_promotion");
  }
}
