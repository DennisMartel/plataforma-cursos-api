<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
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

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [
      "userInfo" => [
        "name" => $this->name,
        "lastname" => $this->lastname,
        "username" => $this->username,
        "email" => $this->email,
        "email_verified" => $this->email_verified_at ? true : false,
        "avatar" => $this->profile_image,
        "created" => $this->created_at->timestamp,
      ],
      "roles" => "client",
      "permissions" => ["access_account"],
      "type" => "Bearer",
      "provider" => "detudev",
    ];
  }

  // Relations
  public function cart()
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

  public function purchases()
  {
    return $this->hasMany(Purchase::class);
  }

  public function purchase_items()
  {
    return $this->hasManyThrough(PurchaseItem::class, Purchase::class);
  }

  public function socialProfiles()
  {
    return $this->hasMany(SocialProfile::class);
  }

  public function reviews()
  {
    return $this->hasMany(Review::class);
  }

  public function verificationCode()
  {
    return $this->hasOne(VerificationCode::class);
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

  public function sendPasswordResetNotification($token)
  {
    $url = env("RESET_PASSWORD_URL") . "?token={$token}&email={$this->email}";
    $this->notify(new ResetPasswordNotification($url));
  }
}
