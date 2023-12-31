<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
  use HasFactory;

  protected $withCount = ["courses"];

  protected $fillable = [
    "title",
    "slug"
  ];

  public function courses()
  {
    return $this->hasMany(Course::class);
  }
}
