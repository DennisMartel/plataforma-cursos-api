<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
  use HasFactory;

  protected $fillable = [
    "reference_code",
    "name",
    "lastname",
    "email",
    "phone_number",
    "total",
    "user_id",
    "country_id",
    "department_id",
    "municipality_id"
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function country()
  {
    return $this->belongsTo(Country::class);
  }

  public function departament()
  {
    return $this->belongsTo(Department::class);
  }

  public function municipality()
  {
    return $this->belongsTo(Municipality::class);
  }

  public function purchase_items()
  {
    return $this->hasMany(PurchaseItem::class);
  }
}
