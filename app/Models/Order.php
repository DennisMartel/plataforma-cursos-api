<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_code",
        "name",
        "lastname",
        "email",
        "phone_number",
        "user_id",
        "country_id",
        "department_id",
        "municipality_id"
    ];
}
