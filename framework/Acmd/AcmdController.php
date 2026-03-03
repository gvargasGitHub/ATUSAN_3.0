<?php

namespace Atusan\Acmd;

class AcmdController
{
  /**
   * Handle
   */
  public static function handle(array $argv): AcmdResolver
  {
    if (count($argv) <= 1) {
      throw new AcmdException('Especifique el comando');
    }
    $nof = count($argv);
    $pairs = [];
    // Recorre la colección de argumentos a partir de la posición 2
    // ya que la posición 0 es para el ejecutable acmd y la posición 1
    // es para el comando.
    // Salta cada 2 posiciones porque evalua argumento valor
    for ($p = 2; $p < $nof; $p += 2) {
      // valida sintaxis --argumento
      if (!preg_match('/^(--[a-z]+$/', $argv[$p])) {
        throw new AcmdException("El argumento {$argv[$p]} no es válido.");
      }
      $v = $p + 1;
      // valida que exista valor
      if ($v >= $nof) {
        throw new AcmdException("{$argv[$p]} requiere de dato.");
      }
      // valida sintaxis valor o "valor con espacios"
      if (!preg_match('/(?:[^\s"]+|"[^"]*"))/', $argv[$v])) {
        throw new AcmdException("{$argv[$v]} no es válido");
      }

      $pairs[$argv[$p]] = $argv[$v];
    }

    return new AcmdResolver($argv[1], $pairs);
  }

  /**
   * Run
   */
  public static function run(AcmdResolver $resolver)
  {
    $resolver->cmd();
  }
}

include 'AcmdResolver.php';
include 'AcmdException.php';
