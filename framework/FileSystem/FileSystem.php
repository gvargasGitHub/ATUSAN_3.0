<?php

namespace Atusan\FileSystem;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class FileSystem
{
  /**
   * Get Class Directory (ChatGPT)
   */
  public static function getClassDirectory($object)
  {
    $r = new ReflectionClass(get_class($object));

    return dirname($r->getFileName());
  }

  /**
   * localeFile (Gemini)
   */
  public static function locateFile($rootPath, $searchTerm, $extension = null)
  {
    if (!is_dir($rootPath)) {
      return false;
    }

    $resultados = [];
    $directory = new RecursiveDirectoryIterator($rootPath);
    $iterator = new RecursiveIteratorIterator($directory);

    // Normalizamos la extensión (quitamos el punto si el usuario lo puso)
    if ($extension) {
      $extension = ltrim(strtolower($extension), '.');
    }

    foreach ($iterator as $file) {
      if ($file->isFile()) {
        $nombreArchivo = $file->getFilename();
        $extActual = strtolower($file->getExtension());

        // 1. Verificamos si el nombre coincide (parcial)
        $coincideNombre = stripos($nombreArchivo, $searchTerm) !== false;

        // 2. Verificamos si la extensión coincide (si se proporcionó una)
        $coincideExt = ($extension === null) || ($extActual === $extension);

        if ($coincideNombre && $coincideExt) {
          $resultados[] = $file->getRealPath();
        }
      }
    }

    return !empty($resultados) ? $resultados : false;
  }
}
