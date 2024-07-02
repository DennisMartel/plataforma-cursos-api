<?php

namespace App\Resolvers;

use Exception;

class PaymentPlatformResolver
{
  public function resolveService($name)
  {
    $service = config("services.{$name}.class");

    if ($service) {
      return resolve($service);
    }

    throw new Exception('The selected payment platform is not in the configuration');
  }
}
