<?php

namespace AtusanCLI\Commands;

abstract class MakeBase
{
  protected $required = [];

  protected $defaults = [];

  function __construct(protected $args)
  {
    $this->required = $this->required();
    $this->defaults = $this->defaults();
  }

  public function handle()
  {
    // Valida parámetros requeridos
    $this->checkRequired();

    // Establece predeterminados
    $this->setDefaults();

    echo "Creando ..." . EOL;

    $this->resolver();
  }

  abstract function required(): array;

  abstract function defaults(): array;

  abstract function resolver();

  private function checkRequired(): void
  {
    # Valida parámetros requeridos
    foreach ($this->required as $req) {
      if (!array_key_exists($req, $this->args)) {
        throw new \Exception("Se requiere parámetro -{$req}");
      }
    }
  }

  protected function setDefaults()
  {
    # Estableciendo parámetros predeterminados
    foreach ($this->defaults as $prm => $val) {
      if (!array_key_exists($prm, $this->args)) {
        $this->args[$prm] = $val;
      }
    }
  }

  protected function checkIfAppExists(string $appName): bool
  {
    return @is_dir(APP_ROOT . DS . 'application' . DS . $appName);
  }

  /**
   * Copy Recursive
   */
  public static function copyR($path, $dest)
  {
    if (is_dir($path)) {
      mkdir($dest, '0777', true);
      $objects = scandir($path);
      if (sizeof($objects) > 0) {
        foreach ($objects as $file) {
          if ($file == "." || $file == "..")
            continue;
          if (is_dir($path . DS . $file))
            self::copyR($path . DS . $file, $dest . DS . $file);
          else
            self::copy($path . DS . $file, $dest . DS . $file);
        }
      }
      return true;
    } elseif (is_file($path))
      return self::copy($path, $dest);
    else
      return false;
  }

  /**
   * Copy File
   */
  public static function copy($path, $dest)
  {
    if (!is_dir(dirname($dest)))
      mkdir(dirname($dest), '0777', true);

    if (is_file($path))
      return copy($path, $dest);
    else
      return false;
  }
}
