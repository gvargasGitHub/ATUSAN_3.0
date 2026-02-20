<?php

namespace Atusan\Xml;

use Atusan\Xml\XmlValidator;

class XmlLoader
{
  /**
   * Load
   */
  public static function load(string $filename): XmlExtended
  {
    libxml_use_internal_errors(true);

    // lee el contenido del archivo para convertir a minúsculas
    $xml = (XmlValidator::fileExists($filename))
      ? simplexml_load_file($filename, XmlExtended::class)
      : self::empty();

    if (!$xml) {
      $errors = ["No se pudo cargar manifiesto ({$filename})"];

      foreach (libxml_get_errors() as $err)
        array_push($errors, "{$err->message} en la línea {$err->line}");

      trigger_error(implode('<br/>', $errors), E_USER_ERROR);
    }

    return $xml;
  }

  /**
   * Empty
   * Crea un objeto SimpleXML con elemento Root.
   */
  public static function empty(): XmlExtended
  {
    return simplexml_load_string("<?xml version='1.0' ?><Root></Root>", XmlExtended::class);
  }
}
