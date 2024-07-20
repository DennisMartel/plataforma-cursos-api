<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialProfile;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialAuthController extends Controller
{
  public function redirectToAuth($driver)
  {
    $drivers = ["google", "facebook"];

    if (in_array($driver, $drivers)) {
      return response()->json([
        "uri" => Socialite::driver($driver)->stateless()->redirect()->getTargetUrl()
      ], 200);
    }
  }

  public function handleAuthCallback($driver)
  {
    try {
      $userSocialite = Socialite::driver($driver)->stateless()->user();
    } catch (ClientException $e) {
      return response()->json([
        "message" => __("messages.credentials_not_match"),
      ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    $socialProfile = SocialProfile::where('social_id', $userSocialite->getId())
      ->where('social_name', $driver)
      ->first();

    if (!$socialProfile) {
      $user = User::where('email', $userSocialite->getEmail())->first();

      if (!$user) {
        $user = User::create([
          "name" => $userSocialite->getName(),
          "username" => $userSocialite->getNickname(),
          "email" => $userSocialite->getEmail(),
        ]);
      }

      $socialProfile = SocialProfile::create([
        'user_id' => $user->id,
        'social_id' => $userSocialite->getId(),
        'social_name' => $driver,
        'social_avatar' => $userSocialite->getAvatar()
      ]);
    }

    $jwt = JWTAuth::claims([
      "login_type" => $driver,
      "social_id" => $userSocialite->getId()
    ])->fromUser($socialProfile->user);

    if (!$jwt) {
      return response()->json([
        "title" => __("messages.sorry"),
        "message" => __("messages.request_not_processed"),
        "buttonText" => "",
        "loggedinUser" => false
      ], 400);
    }

    return response()->json([
      "user" => $socialProfile->user,
      "loggedinUser" => true,
      "authorization" => [
        "token" => $jwt,
        "type" => "Bearer"
      ]
    ]);
  }
}
