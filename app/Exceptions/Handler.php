<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
  /**
   * The list of the inputs that are never flashed to the session on validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation',
  ];

  /**
   * Register the exception handling callbacks for the application.
   */
  public function register(): void
  {
    $this->reportable(function (Throwable $e) {
      //
    });

    $this->renderable(function (NotFoundHttpException $e, $request) {
      if ($request->is("cms/*")) {
        if (Storage::disk("public")->exists("resources/noentry.json")) {
          return response()->json(json_decode(Storage::disk("public")->get("resources/noentry.json")), 404);
        }
      }
    });
  }
}
