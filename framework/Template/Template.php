<?php

namespace Atusan\Template;

class Template
{
  public static $app;
  public static $module;


  /**
   * Render
   */
  static public function render($module)
  {
    # Esta declaración de $app hará disponible al objeto Application
    # dentro de los "layouts" y "templates"
    self::$app = $module->app;
    self::$module = $module;

    // include(APP_DIRECTORY . DS . "{$module->template}/" . basename($module->template) . ".view.php");
    static::locateFile(APP_DIRECTORY . DS . "{$module->template}", basename($module->template) . ".view");
  }

  static public function extend($layout)
  {
    static::locateFile(APP_DIRECTORY, $layout);
  }

  static public function renderError(string $message, array $trace = [])
  {
    include 'error.view.php';
  }

  static public function renderNotice(string $message)
  {
    include 'notice.view.php';
  }

  static public function renderWarning(string $message)
  {
    include 'warning.view.php';
  }

  /**
   * 
   */
  static public function locateFile(string $root, string $name): void
  {
    // Obtiene colección de directorios
    $directories = scandir($root);

    if ($directories === false)
      trigger_error("El sistema no puede encontrar la carpeta específica {$root}", E_USER_ERROR);
    // Recorre la colección de directorios hasta encontrar el archivo
    foreach ($directories as $directory) {
      if ($directory == '.' || $directory == '..') continue;
      // Obtiene el elemento (directorio | archivo)
      $filename = $root . DS . $directory;
      // Valida si es directorio o archivo
      if (is_dir($filename))
        static::locateFile($filename, $name);
      else {
        $bn = basename($filename, '.php');

        if ($bn == $name) {
          require $filename;
          return;
        }
      }
    }
  }
}
