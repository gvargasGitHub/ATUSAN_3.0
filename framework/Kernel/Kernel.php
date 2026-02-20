<?php

namespace Atusan\Kernel;

use Atusan\Request\OutgoingRequest;
use Atusan\Route\Route;
use Atusan\Security\SecurityMiddleware;
use Atusan\Session\AppSession;

class Kernel
{
  /**
   * 
   */
  static public function handle(): OutgoingRequest
  {
    Route::implement();

    return OutgoingRequest::capture();
  }

  static public function execute(OutgoingRequest $request)
  {
    [$app, $controller, $routeType] = Route::resolve();

    SecurityMiddleware::handle($request);

    if ($routeType->middlewareState) {
      if (!AppSession::get($routeType->middlewareFilter)) $controller = Route::redirect($app, $routeType->middlewareRedirectUri);
    }

    Route::process($controller, $routeType);
  }
}
