<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  protected $appends = [
    "profile_image",
  ];

  public function carts()
  {
    return $this->hasMany(Cart::class);
  }

  public function dictated_courses()
  {
    return $this->hasMany(Course::class);
  }

  public function enrolled_courses()
  {
    return $this->belongsToMany(Course::class);
  }

  public function lessons()
  {
    return $this->belongsToMany(Lesson::class);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function order_items()
  {
    return $this->hasManyThrough(OrderItem::class, Order::class);
  }

  public function socialProfiles()
  {
    return $this->hasMany(SocialProfile::class);
  }

  public function reviews()
  {
    return $this->hasMany(Review::class);
  }

  // Attribute
  public function getProfileImageAttribute()
  {
    $socialProfiles = $this->socialProfiles()->first();

    if ($socialProfiles) {
      return $socialProfiles->social_avatar;
    }

    if ($this->profile_photo_path) {
      return $this->profile_photo_path;
    }

    return asset("images/default-avatar.png");
  }
}
