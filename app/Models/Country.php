<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
  use HasFactory;

  protected $fillable = [
    "country",
    "country_code",
    "iso_code",
    "alpha_three_code",
    "flag_icon"
  ];

  public function departments()
  {
    return $this->hasMany(Department::class);
  }

  public function purchases()
  {
    return $this->hasMany(Purchase::class);
  }
}
