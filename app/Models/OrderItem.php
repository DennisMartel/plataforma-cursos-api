<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
  use HasFactory;

  protected $fillable = [
    "course_name",
    "sale_price",
    "regular_price",
    "image_course",
    "quantity",
    "purchase_id"
  ];

  public function purchase()
  {
    return $this->belongsTo(Purchase::class);
  }
}
