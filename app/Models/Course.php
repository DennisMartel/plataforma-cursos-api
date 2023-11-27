<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
  use HasFactory;

  const DRAFT = 1;
  const REVIEW = 2;
  const APPROVED = 3;
  const REFUSED = 4;

  protected $withCount = ["students", "reviews"];

  protected $fillable = [
    "title",
    "subtitle",
    "slug",
    "description",
    "price",
    "status",
    "user_id",
    "category_id",
    "level_id"
  ];

  protected $appends = [
    "teacher_image",
    "teacher_name",
    "image_course",
    "rating"
  ];

  protected $hidden = [
    "user_id",
    "category_id",
    "level_id",
    "created_at",
    "updated_at",
    "status",
    "teacher",
    "image",
    "reviews"
  ];

  public function carts()
  {
    return $this->belongsToMany(Cart::class);
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function level()
  {
    return $this->belongsTo(Level::class);
  }

  public function teacher()
  {
    return $this->belongsTo(User::class, "user_id");
  }

  public function students()
  {
    return $this->belongsToMany(User::class);
  }

  public function sections()
  {
    return $this->hasMany(Section::class);
  }

  public function image()
  {
    return $this->morphOne(Image::class, "imageable");
  }

  public function lessons()
  {
    return $this->hasManyThrough(Lesson::class, Section::class);
  }

  public function reviews()
  {
    return $this->hasMany(Review::class);
  }

  // Scopes
  public function scopeSearch($query, $term)
  {
    if ($query) {
      return $query->where("title", "LIKE", "%{$term}%");
    }
  }

  // Attributes
  public function getTeacherImageAttribute()
  {
    $socialProfiles = $this->teacher->socialProfiles()->first();

    if ($socialProfiles !== null) {
      return $socialProfiles->social_avatar;
    }

    if ($this->teacher->profile_photo_path !== null) {
      return $this->author->profile_photo_path;
    }

    return asset("images/default-avatar.png");
  }

  public function getTeacherNameAttribute()
  {
    return $this->teacher->name;
  }

  public function getImageCourseAttribute()
  {
    return Storage::url($this->image->url);
  }

  public function getRatingAttribute()
  {
    if ($this->reviews_count) {
      return round($this->reviews->avg("rating"), 1);
    }

    return 5;
  }
}
