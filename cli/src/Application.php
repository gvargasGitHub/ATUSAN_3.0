<?php

namespace AtusanCLI;

use AtusanCLI\Commands\MakeApp;
use AtusanCLI\Commands\MakeModel;
use AtusanCLI\Commands\MakeModule;
use AtusanCLI\Commands\MakeNested;
use AtusanCLI\Commands\MakeService;

class Application
{
  protected $commands = [];

  public function __construct()
  {
    $this->register('make:app', MakeApp::class);
    $this->register('make:module', MakeModule::class);
    $this->register('make:nested', MakeNested::class);
    $this->register('make:model', MakeModel::class);
    $this->register('make:service', MakeService::class);
  }

  protected function register($name, $class)
  {
    $this->commands[$name] = $class;
  }

  public function run($argv)
  {
    $command = $argv[1] ?? null;

    if (!$command) {
      $this->showHelp();
      return;
    }

    try {
      if (!isset($this->commands[$command])) {
        echo "Comando {$command} no encontrado\n";
        return;
      }
      // analiza la lista de argumentos y retorna
      // colección de pares parametro=valor
      $pairs = $this->parse(array_slice($argv, 2));

      $class = $this->commands[$command];
      $instance = new $class($pairs);

      $instance->handle();
    } catch (\Exception $ex) {
      echo 'Error: ' . $ex->getMessage() . EOL . EOL;
    }
  }

  public static function parse(array $argv): array
  {
    $nof = count($argv);
    $pairs = [];
    // Salta cada 2 posiciones porque evalua argumento valor
    for ($p = 0; $p < $nof; $p += 2) {
      // valida sintaxis --argumento
      if (!preg_match('/^--[a-z]+$/', $argv[$p])) {
        throw new \Exception("El argumento {$argv[$p]} no es válido.");
      }
      $v = $p + 1;
      // valida que exista valor
      if ($v >= $nof) {
        throw new \Exception("{$argv[$p]} requiere de dato.");
      }
      // valida sintaxis valor o "valor con espacios"
      if (!preg_match('/(?:[^\s"]+|"[^"]*")/', $argv[$v])) {
        throw new \Exception("{$argv[$v]} no es válido");
      }

      $pairs[$argv[$p]] = $argv[$v];
    }

    return $pairs;
  }

  protected function showHelp()
  {
    echo "ATUSAN CLI\n\n";
    echo "Comandos disponibles:\n";

    foreach (['app', 'module', 'model', 'service'] as $w)
      $this->how(['--what' => $w]);
  }

  /**
   * How Command
   */
  protected function how($pairs)
  {
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
        echo "comando {$pairs['--what']} desconocido." . EOL;
    }
  }
}
