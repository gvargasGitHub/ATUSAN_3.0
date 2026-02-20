<?php

namespace Atusan\Bootstrap;

use Atusan\Autoloader\Autoloader;
use Atusan\Errors\ErrorsController  as Errors;
use Atusan\Kernel\Kernel;
use Dotenv\Dotenv;

class Bootstrap
{
  static public function app()
  {
    // Root/.env contiene la definición de la Aplicación
    $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->safeLoad();

    // Constantes de directorios
    define('DS', DIRECTORY_SEPARATOR);
    define('APP_ROOT', dirname(__DIR__, 2));
    define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
    define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL));

    // define ubicación de Aplicación
    define('APP_NAME', $_ENV['APP_NAME']);
    define('APP_DIRECTORY', APP_ROOT . DS . $_ENV['APPS_DIRECTORY'] . DS . APP_NAME);

    // Autoloader específico para las clases de App
    Autoloader::init(APP_DIRECTORY);

    // Registro de manejo de errores
    Errors::register();

    // 
    Kernel::execute(Kernel::handle());
  }
}
