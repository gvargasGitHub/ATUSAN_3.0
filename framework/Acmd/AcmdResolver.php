<?php

/**
 * Resolver
 * Comandos:
 * app: Crea una nueva aplicación dentro de application.
 */

namespace Atusan\Acmd;

use Atusan\FileSystem\FileSystem;
use Atusan\Types\AcmdModuleType;

class AcmdResolver
{
  function __construct(public $cmd, public $pairs) {}

  function executeCommand()
  {
    if (!method_exists($this, $this->cmd)) {
      throw new AcmdException("El comando {$this->cmd} no es reconocido");
    }

    $this->{$this->cmd}($this->pairs);
  }

  // ----------------------------------
  //  Comandos
  // ----------------------------------
  /**
   * App Command
   * Nueva Aplicación.
   * argumentos:
   *  - name  : Nombre de la carpeta de la aplicación.
   *  - title : Título de la pestaña de navegador.
   *  - start : Nombre del módulo de acceso.
   */
  function app($pairs)
  {
    # Valida parámetros requeridos
    $this->checkRequiredArguments(['--name'], $pairs);

    # Estableciendo parámetros predeterminados
    foreach (
      [
        '--title' => $pairs['--name'],
        '--start' => 'modAcceso'
      ] as $prm => $val
    ) {
      if (!array_key_exists($prm, $pairs)) {
        $pairs[$prm] = $val;
      }
    }
    // Valida si ya existe la carpeta de la Aplicación
    if ($this->checkIfAppExists($pairs['--name']))
      throw new AcmdException("{$pairs['--name']} ya existe.");

    $appDir = APP_ROOT . DS . 'application' . DS . $pairs['--name'];

    // Copia plantilla de la Aplicación
    FileSystem::copyR(
      ACMD_ROOT . DS . 'templates' . DS . 'newapp',
      APP_ROOT . DS . 'application' . DS . $pairs['--name']
    );

    // Edita contenidos
    $xml = file_get_contents($appDir . DS . 'Manifest.xml');
    $xml = str_replace('app_name', $pairs['--name'], $xml);
    $xml = str_replace('app_title', $pairs['--title'], $xml);
    $xml = str_replace('app_start', $pairs['--start'], $xml);
    file_put_contents($appDir . DS . 'Manifest.xml', $xml);

    // Copia recursos CSS y JS
    FileSystem::copyR(
      ACMD_ROOT . DS . 'templates' . DS . 'statics' . DS . 'css',
      APP_ROOT . DS . 'public' . DS . $pairs['--name'] . DS . 'css'
    );
    FileSystem::copyR(
      ACMD_ROOT . DS . 'templates' . DS . 'statics' . DS . 'js',
      APP_ROOT . DS . 'public' . DS . $pairs['--name'] . DS . 'js'
    );

    // Copia archivo .env
    FileSystem::copy(
      ACMD_ROOT . DS . 'templates' . DS . 'statics' . DS . 'env' . DS . '.env',
      APP_ROOT . DS . '.env-' . $pairs['--name']
    );
    $env = file_get_contents(APP_ROOT . DS . '.env-' . $pairs['--name']);
    $env = str_replace('app_name', $pairs['--name'], $env);
    file_put_contents(APP_ROOT . DS . '.env-' . $pairs['--name'], $env);

    echo "La aplicación {$pairs['--name']} se creó exitosamente." . EOL;
  }

  /**
   * Module Command
   * argumentos:
   * - app  : Nombre de la Aplicación (requerido).
   * - name : Nombre del módulo (requerido).
   * - type : Tipo del módulo (requerido)(basic|nested).
   * - title: Título del módulo (default name).
   * - root: Carpeta contenedora (default Modules).
   * - parent: Clase padre del módulo (default AppModuleParent).
   * - template: Plantilla del módulo (default frame).
   */
  function module($pairs)
  {
    # Valida parámetros requeridos
    $this->checkRequiredArguments(['--app', '--name', '--type'], $pairs);

    // Valida si existe la carpeta de la Aplicación
    if (!$this->checkIfAppExists($pairs['--app']))
      throw new AcmdException("{$pairs['--app']} no existe.");

    if (!preg_match('/(basic|nested)/', $pairs['--type']))
      throw new AcmdException("El tipo {$pairs['--type']} no se reconoce.\n"
        . 'Opciones: basic o nested' . EOL);

    $moduleType = $this->getModuleType($pairs['--type']);

    # Resolviendo ruta
    $pairs['--path'] = $pairs['--name'];
    $pairs['--name'] = basename($pairs['--name']);
    # Estableciendo parámetros predeterminados
    foreach (
      [
        '--title' => $pairs['--name'],
        '--root' => 'Modules',
        '--parent' => $moduleType->parent,
        '--template' => $moduleType->template
      ] as $prm => $val
    ) {
      if (!array_key_exists($prm, $pairs)) {
        $pairs[$prm] = $val;
      }
    }

    $modulePath = APP_ROOT . DS . 'application'
      . DS . $pairs['--app'] . DS . $pairs['--root']
      . DS . $pairs['--path'];

    // Valida si ya existe la carpeta del Módulo
    if (@is_dir($modulePath))
      throw new AcmdException("{$pairs['--path']} ya existe.");

    // Copia plantilla del Módulo
    FileSystem::copyR(
      ACMD_ROOT . DS . 'templates' . DS . $moduleType->folder,
      $modulePath
    );

    // Edita contenidos
    $xml = file_get_contents($modulePath . DS . 'Components.xml');
    $xml = str_replace('module_title', $pairs['--title'], $xml);
    $xml = str_replace('module_template', 'Templates/' . $pairs['--template'], $xml);
    file_put_contents($modulePath . DS . 'Components.xml', $xml);

    $php = file_get_contents($modulePath . DS . 'Controller.php');
    $php = str_replace('module_name', $pairs['--name'], $php);
    $php = str_replace('module_parent', $pairs['--parent'], $php);
    file_put_contents($modulePath . DS . 'Controller.php', $php);

    $view = file_get_contents($modulePath . DS . 'View.php');
    $view = str_replace('module_name', $pairs['--name'], $view);
    file_put_contents($modulePath . DS . 'View.php', $view);

    echo "El módulo {$pairs['--name']} se creó exitosamente." . EOL;
  }

  /**
   * Model Command
   * argumentos
   * - app  : Nombre de la Aplicación (requerido).
   * - name : Nombre del modelo (requerido).
   */
  function model($pairs)
  {
    # Valida parámetros requeridos
    $this->checkRequiredArguments(['--app', '--name'], $pairs);

    // Valida si existe la carpeta de la Aplicación
    if (!$this->checkIfAppExists($pairs['--app']))
      throw new AcmdException("{$pairs['--app']} no existe.");

    # Resolviendo ruta
    $pairs['--path'] = dirname($pairs['--name']);
    $pairs['--name'] = basename($pairs['--name']);

    # Estableciendo parámetros predeterminados
    foreach (
      [
        '--root' => 'Models'
      ] as $prm => $val
    ) {
      if (!array_key_exists($prm, $pairs)) {
        $pairs[$prm] = $val;
      }
    }

    $modelPath = APP_ROOT . DS . 'application'
      . DS . $pairs['--app'] . DS . $pairs['--root']
      . DS . $pairs['--path'];

    // Valida si ya existe el Modelo
    if (@file_exists($modelPath . DS . $pairs['--name']))
      throw new AcmdException("{$pairs['--name']} ya existe.");

    // Copia plantilla del Modelo
    FileSystem::copy(
      ACMD_ROOT . DS . 'templates' . DS . 'newmodel' . DS . 'Model.php',
      $modelPath . DS . 'Model.php'
    );

    // Edita contenidos
    $content = file_get_contents($modelPath . DS . 'Model.php');
    $content = str_replace('model_name', $pairs['--name'], $content);

    file_put_contents($modelPath . DS . 'Model.php', $content);
    rename($modelPath . DS . 'Model.php', $modelPath . DS . "{$pairs['--name']}.php");

    echo "El modelo {$pairs['--name']} se creó exitosamente." . EOL;
  }

  /**
   * Service Command
   * argumentos
   * - app  : Nombre de la Aplicación (requerido).
   * - name : Nombre del modelo (requerido).
   */
  function service($pairs)
  {
    # Valida parámetros requeridos
    $this->checkRequiredArguments(['--app', '--name'], $pairs);

    // Valida si existe la carpeta de la Aplicación
    if (!$this->checkIfAppExists($pairs['--app']))
      throw new AcmdException("{$pairs['--app']} no existe.");

    # Resolviendo ruta
    $pairs['--path'] = dirname($pairs['--name']);
    $pairs['--name'] = basename($pairs['--name']);

    # Estableciendo parámetros predeterminados
    foreach (
      [
        '--root' => 'Services'
      ] as $prm => $val
    ) {
      if (!array_key_exists($prm, $pairs)) {
        $pairs[$prm] = $val;
      }
    }

    $modelPath = APP_ROOT . DS . 'application'
      . DS . $pairs['--app'] . DS . $pairs['--root']
      . DS . $pairs['--path'];

    // Valida si ya existe el Modelo
    if (@file_exists($modelPath . DS . $pairs['--name']))
      throw new AcmdException("{$pairs['--name']} ya existe.");

    // Copia plantilla del Modelo
    FileSystem::copy(
      ACMD_ROOT . DS . 'templates' . DS . 'newservice' . DS . 'Service.php',
      $modelPath . DS . 'Service.php'
    );

    // Edita contenidos
    $content = file_get_contents($modelPath . DS . 'Service.php');
    $content = str_replace('service_name', $pairs['--name'], $content);

    file_put_contents($modelPath . DS . 'Service.php', $content);
    rename($modelPath . DS . 'Service.php', $modelPath . DS . "{$pairs['--name']}.php");

    echo "El servicio {$pairs['--name']} se creó exitosamente." . EOL;
  }

  /**
   * Help Command
   */
  protected function help($pairs)
  {
    echo str_repeat('-', 40) . EOL;
    echo "Acmd Command Line.\n";
    echo str_repeat('-', 40) . EOL;

    if (array_key_exists('--what', $pairs))
      $this->how($pairs);
    else {
      foreach (['app', 'module', 'model', 'service'] as $w)
        $this->how(['--what' => $w]);
    }
  }
  /**
   * How Command
   */
  protected function how($pairs)
  {
    # Valida parámetros requeridos
    $this->checkRequiredArguments(['--what'], $pairs);

    switch ($pairs['--what']) {
      case 'app':
        echo "New Application.\n";
        echo "app --name ? --title ? --start ?\n";
        echo str_repeat('-', 40) . EOL;
        break;
      case 'module':
        echo "New Module.\n";
        echo "module --app ? --name ? --type [basic*|nested] --start --parent ? --template ? --title ?\n";
        echo str_repeat('-', 40) . EOL;
        break;
      case 'model':
        echo "New Model.\n";
        echo "model --app ? --name ?\n";
        echo str_repeat('-', 40) . EOL;
        break;
      case 'service':
        echo "New Service.\n";
        echo "service --app ? --name ?\n";
        echo str_repeat('-', 40) . EOL;
        break;
      default:
        throw new AcmdException("{$pairs['--what']} is unknow");
    }
  }

  private function checkRequiredArguments(array $args, array $pairs): bool
  {
    # Valida parámetros requeridos
    foreach ($args as $prm) {
      if (!array_key_exists($prm, $pairs)) {
        throw new AcmdException("Se requiere parámetro -{$prm}");
      }
    }

    return true;
  }

  protected function checkIfAppExists(string $appName): bool
  {
    return @is_dir(APP_ROOT . DS . 'application' . DS . $appName);
  }

  private function getModuleType($type): AcmdModuleType
  {
    return ($type == 'nested')
      ? new AcmdModuleType('AppNestedParent', 'nested', 'newnested')
      : new AcmdModuleType('AppModuleParent', 'frame', 'newmodule');
  }
}
