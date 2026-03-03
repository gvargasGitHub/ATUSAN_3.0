<?php

namespace Atusan\Acmd;

class AcmdResolver
{
  function __construct(public $cmd, public $pairs) {}

  function cmd()
  {
    if (!method_exists($this, $this->cmd)) {
      throw new AcmdException("El comando {$this->cmd} no es reconocido");
    }

    $this->{$this->cmd}($this->pairs);
  }

  protected $appTypes = ['modular', 'tabframe'];
  /**
   * App Command
   * Nueva Aplicación.
   */
  function app($pairs)
  {
    # Valida parámetros requeridos
    foreach (['type', 'name'] as $prm) {
      if (!array_key_exists($prm, $pairs)) {
        throw new AcmdException("Se requiere parámetro -{$prm}");
      }
    }
    # Estableciendo parámetros predeterminados
    foreach (
      [
        'title' => $pairs['app']
      ] as $prm => $val
    ) {
      if (!array_key_exists($prm, $pairs)) {
        $pairs[$prm] = $val;
      }
    }
    # Valida tipos de aplicaciones
    if (!array_search($pairs['type'], self::$appTypes)) {
      throw new AcmdException("El tipo {$pairs['type']} no existe");
    }

    # (i) Establece app como componente fijo
    $pairs['component'] = "app";

    // # /!\ Valida el component+tipo
    // if (@!is_dir(ATS_DIRECTORY_ROOT . 'acm' . DS . 'templates' . DS . $pairs['component'] . DS . $pairs['type'])) {
    //   throw new AcmdException("{$pairs['type']} no es un tipo de componente válido.");
    // }
    // $source = ATS_DIRECTORY_ROOT . 'acm' . DS . 'templates' . DS . $pairs['component'] . DS . $pairs['type'];

    // # /!\ Evalua el componente + tipo para realizar tareas específicas
    // self::{$pairs['component']}($source, $pairs);
    $this->help();
  }

  // ----------------------------------
  //  Comandos
  // ----------------------------------
  /**
   * Help Command
   */
  protected function help()
  {
    echo "Acmd Linea de Comandos.\n";
    echo "Iniciar una nueva aplicación.\n";
    echo "app --name ? --type [modular|tabframe] --title ?\n\n";
    echo "Crear un nuevo módulo.\n";
    echo "module --name ? --type [access|basic*|nested] --extends [?|AppModuleParent|AppNestedParent] --template ? --title ?\n";
  }
}
