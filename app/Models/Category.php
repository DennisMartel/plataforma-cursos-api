<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;
  const ACTIVE = 1;
  const INACTIVE = 2;

  protected $withCount = ["courses"];

  protected $fillable = [
    "title",
    "slug",
    "icon",
    "image",
    "status"
  ];

  public function courses()
  {
    return $this->hasMany(Course::class)->where("status", Course::APPROVED);
  }

  public function promotions()
  {
    return $this->belongsToMany(Promotion::class, "category_promotion");
  }
}
