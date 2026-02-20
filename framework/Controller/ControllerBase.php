<?php

namespace Atusan\Controller;

use Atusan\Xml\XmlExtended;
use Atusan\Xml\XmlLoader;

abstract class ControllerBase implements ControllerInterface
{
  /**
   * @var $xmlRef
   */
  protected $xmlRef;

  /**
   * @var XmlExtended $xml
   */
  protected $xml;

  /**
   * @var Array $namespaces
   */
  protected $namespaces;
  /**
   * Controller
   */
  function __construct(public $name) {}

  /**
   * 
   */
  protected function injectXml(): void
  {
    # Establece manifiesto
    $this->xml = (is_string($this->xmlRef)) ? XmlLoader::load($this->xmlRef) : $this->xmlRef;

    // obtiene nombres de espacio
    $this->namespaces = array_merge(
      ['' => ''],
      $this->xml->getDocNamespaces(true, true)
    );

    # integra los atributos de "root" a la clase
    foreach ($this->namespaces as $url) {
      foreach ($this->xml->attributes($url) as $k => $v)
        if (isset($v)) $this->$k = (string) $v;
    }

    if (property_exists($this, 'layout')) {
      $xmlext = XmlLoader::load($this->layout);

      # integra atributos de la raiz de la extension al controlador
      foreach ($xmlext->attributes() as $k => $v)
        if (isset($v)) $this->$k = (string) $v;

      # integra la extensión al controlador
      $this->xml->extend($xmlext);
    }

    unset($this->xmlRef);
  }
}
