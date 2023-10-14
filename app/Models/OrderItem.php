<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "course_name",
        "sale_price",
        "regular_price",
        "image_course",
        "quantity",
        "order_id"
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
