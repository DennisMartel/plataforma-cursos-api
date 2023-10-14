<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "zip_code",
        "department_id"
    ];

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
