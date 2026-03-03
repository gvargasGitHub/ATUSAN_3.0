<?php

namespace Atusan\Kernel;

use Atusan\Request\OutgoingRequest;
use Atusan\Route\Route;
use Atusan\Security\SecurityMiddleware;
use Atusan\Session\AppSession;

class Kernel
{
  /**
   * Handle
   */
  static public function handle(): OutgoingRequest
  {
    Route::implement();

    return OutgoingRequest::capture();
  }

  /**
   * Execute
   */
  static public function execute(OutgoingRequest $request)
  {
    [$app, $controller, $routeType] = Route::resolve();

    SecurityMiddleware::handle($request);
    // handle inicia la sesión de la App

    if ($routeType->middlewareState) {
      if (!AppSession::get($routeType->middlewareFilter)) $controller = Route::redirect($app, $routeType->middlewareRedirectUri);
    }

    Route::process($controller, $routeType);
  }
}
