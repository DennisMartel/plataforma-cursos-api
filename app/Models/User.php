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
use Tymon\JWTAuth\Facades\JWTAuth;

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
    "shopping_cart"
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
    $loginType = null;
    $socialId = null;

    if (auth()->guard("api")->user()) {
      try {
        $token = JWTAuth::parseToken();
        $loginType = $token->getClaim("login_type");
        $socialId = $token->getClaim("social_id");
      } catch (\Exception) {
      }
    }

    $socialProfiles = $this->socialProfiles()
      ->where("social_name", $loginType)
      ->where("social_id", $socialId)
      ->first();

    if ($socialProfiles) {
      return $socialProfiles->social_avatar;
    }

    if ($this->profile_photo_path) {
      return $this->profile_photo_path;
    }

    return asset("images/default-avatar.png");
  }

  public function getShoppingCartAttribute()
  {
    return $this->cart()
      ->with(["course"])
      ->get()
      ->map(function ($cart) {
        return [
          "id" => $cart->course->id,
          "cart_id" => $cart->id,
          "title" => $cart->course->title,
          "slug" => $cart->course->slug,
          "rating" => $cart->course->rating,
          "price" => number_format((float)$cart->course->price, 2),
          "discount_price" => $cart->course->discount_price,
          "last_update" => $cart->course->last_update,
          "image" => $cart->course->image_course,
          "teacher_name" => $cart->course->teacher_name,
          "teacher_pic" => $cart->course->teacher_image,
          "lessons_count" => $cart->course->lessons_count,
          "is_enrolled" => $cart->course->is_enrolled,
          "category" => $cart->course->category->title,
          "level" => $cart->course->level->title
        ];
      })
      ->values();
  }

  public function sendPasswordResetNotification($token)
  {
    $url = env("RESET_PASSWORD_URL") . "?token={$token}&email={$this->email}";
    $this->notify(new ResetPasswordNotification($url));
  }
}
