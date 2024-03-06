<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    try {
      $user = JWTAuth::parseToken()->authenticate();

      if ($user == null) :
        return response()->json(["message" => "Unauthorized"], HttpResponse::HTTP_UNAUTHORIZED);
      endif;
    } catch (JWTException $e) {
      if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
        return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
      }

      if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
        return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
      }

      return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
    }

    return $next($request);
  }
}
