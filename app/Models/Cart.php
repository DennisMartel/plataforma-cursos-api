<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        "quantity",
        "course_id",
        "user_id"
    ];

    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
