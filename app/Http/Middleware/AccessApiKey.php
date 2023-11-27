<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessApiKey
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $apiKey = $request->headers->get("x_api-key");

    if ($apiKey !== env("X_API_KEY")) {
      return response()->json([
        "message" => "Forbidden"
      ], 403);
    }

    return $next($request);
  }
}
