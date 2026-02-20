<?php

namespace Atusan\Xml;

class XmlInjector
{
  static public function inject(&$tarjet, $xmlRef): void
  {
    $tarjet->xml = ($xmlRef instanceof XmlExtended) ? $xmlRef : XmlLoader::load($xmlRef);

    # integra los atributos de "root" a la clase
    foreach ($tarjet->xml->attributes() as $k => $v)
      if (isset($v)) $tarjet->$k = (string) $v;

    if (property_exists($tarjet, 'layout')) {
      $xmlext = XmlLoader::load($tarjet->layout);

      # integra atributos de la raiz de la extension al controlador
      foreach ($xmlext->attributes() as $k => $v)
        if (isset($v)) $tarjet->$k = (string) $v;

      # integra la extensión al controlador
      $tarjet->xml->extend($xmlext);
    }
  }
}
