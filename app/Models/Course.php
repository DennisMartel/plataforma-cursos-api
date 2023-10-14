<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    const DRAFT = 1;
    const REVIEW = 2;
    const APPROVED = 3;
    const REFUSED = 4;

    protected $fillable = [
        "title",
        "subtitle",
        "slug",
        "price",
        "status",
        "user_id",
        "category_id",
        "level_id"
    ];

    public function carts() {
        return $this->belongsToMany(Cart::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function teacher() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function students() {
        return $this->belongsToMany(User::class);
    }

    public function sections() {
        return $this->hasMany(Section::class);
    }

    public function images() {
        return $this->morphOne(Image::class, "imageable");
    }

    public function lessons() {
        return $this->hasManyThrough(Lesson::class, Section::class);
    }
}
