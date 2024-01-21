<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
  use HasFactory;

  protected $guarded = ["id"];
  protected $appends = ["isExpired"];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // attributes
  public function getIsExpiredAttribute()
  {
    return Carbon::now()->diffInMinutes($this->created_at) > 60;
  }
}
