<?php

namespace Atusan\Acmd;

class AcmdBootstrap
{
  static function app(): string
  {
    // Constantes de directorios
    define('DS', DIRECTORY_SEPARATOR);
    define('EOL', "\n");
    define('APP_ROOT', dirname(__DIR__, 2));
    define('ACMD_ROOT', __DIR__);

    return AcmdController::class;
  }
}
