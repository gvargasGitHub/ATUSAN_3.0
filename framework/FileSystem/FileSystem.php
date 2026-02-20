<?php

namespace Atusan\FileSystem;

class FileSystem
{
  /**
   * Get Applications Directory
   */
  static public function getApplicationDirectory(): array
  {
    if (!is_dir(APP_ROOT . '/application/'))
      trigger_error('Directorio "application" no existe.', E_USER_ERROR);

    $appsDir = scandir(APP_ROOT . '/application/');

    if ($appsDir === false) trigger_error('El sistema no puede encontrar la carpeta específica "application"', E_USER_ERROR);

    array_splice($appsDir, 0, 2);

    if (count($appsDir) == 0) trigger_error('No existe ninguna aplicación.', E_USER_ERROR);

    return $appsDir;
  }

  /**
   * 
   */
  public static function getFirstApplicationDirectory(): string
  {
    return self::getApplicationDirectory()[0];
  }
}
