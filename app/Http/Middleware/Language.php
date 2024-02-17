<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Language
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $language = $request->headers->get("language") ?? 'es';

    $configLanguage = config("languages")[$language];

    setlocale(LC_TIME, $configLanguage[1] . '.utf8');

    Carbon::setLocale($language);

    App::setLocale($language);

    return $next($request);
  }
}
