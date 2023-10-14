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

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function departament() {
        return $this->belongsTo(Department::class);
    }

    public function municipality() {
        return $this->belongsTo(Municipality::class);
    }

    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }
}
