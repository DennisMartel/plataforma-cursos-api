<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  use HasFactory;

  protected $fillable = [
    "name",
    "iso_code",
    "country_id"
  ];

  public function country()
  {
    return $this->belongsTo(Country::class);
  }

  public function municipalities()
  {
    return $this->hasMany(Municipality::class);
  }

  public function purchases()
  {
    return $this->hasMany(Purchase::class);
  }
}
