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
}
