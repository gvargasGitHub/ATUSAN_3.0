<?php

namespace Atusan\Security;

use Atusan\Log\Log;
use Atusan\Request\OutgoingRequest;
use Atusan\Response\Response;
use Atusan\Session\AppSession;

class SecurityMiddleware
{
  /**
   * Handle
   * Bootstrap -> Kernel::execute
   */
  public static function handle(OutgoingRequest $request): void
  {
    self::startSession();
    self::securityHeaders();
    self::checkHttpMethod($request);
  }

  private static function startSession(): void
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      AppSession::start();
    }

    Log::info('Sesion iniciada');
  }

  private static function securityHeaders(): void
  {
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin');
    // header("Content-Security-Policy: default-src 'self';");
  }

  private static function checkHttpMethod(OutgoingRequest $request): void
  {
    $allowed = ['GET', 'POST', 'PUT', 'DELETE'];

    if (!in_array($request->method(), $allowed, true)) {
      http_response_code(405);
      exit(Response::instance()->error('Método ' . $request->method() . ' no permitido.'));
    }
  }

  public static function generateCsrf(): string
  {
    return Csrf::generate();
  }

  public static function validateCsrf(OutgoingRequest $request): bool
  {
    $token =
      $request->get('csrf_token') ??
      $request->header('X-CSRF-TOKEN');

    return Csrf::validate($token);
  }

  public static function regenerateCsrf(): void
  {
    Csrf::regenerate();
  }
}
