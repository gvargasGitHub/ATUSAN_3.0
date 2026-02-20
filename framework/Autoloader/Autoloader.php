<?php

namespace Atusan\Autoloader;

/**

 */
class Autoloader
{
  /**
   * 
   */
  protected static $loader;

  public static function init($root)
  {
    if (self::$loader == NULL)
      self::$loader = new self($root);

    return self::$loader;
  }

  function __construct(private $root)
  {
    # param throw : true activa disparo de excepciones
    # param prepend: false coloca el "autoload" al final de la cola
    spl_autoload_register([$this, 'resolve'], true, false);
  }

  public function resolve(string $className): void
  {
    if (class_exists($className)) return;

    # inicia localización de la clase
    $this->locateClass($this->root, basename($className));
  }

  protected $alias = [
    'Controller',
    'Service',
    'Model'
  ];

  protected function locateClass(string $root, string $className): void
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
        $this->locateClass($filename, $className);
      else {
        $bn = basename($filename, '.php');

        if ($bn == $className || (basename($root) == $className && in_array($bn, $this->alias))) {
          require $filename;
          return;
        }
      }
    }
  }
}
