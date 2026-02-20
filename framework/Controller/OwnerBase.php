<?php

namespace Atusan\Controller;

use Atusan\Log\Log;
use Atusan\Request\OutgoingRequest;
use Atusan\Response\Response;
use Atusan\Xml\XmlExtended;

abstract class OwnerBase extends ControllerBase
{
  /**
   * @var XMLExtended $collection
   */
  protected $collection = [];

  /**
   * @var Array components
   */
  protected $components = [];

  /**
   * 
   */
  protected $request;

  /**
   * 
   */
  protected $response;

  /**
   * 
   */
  protected $owner;

  /**
   * 
   */
  protected $parent;

  /**
   * Controller
   */
  function __construct($name, public $directory)
  {
    parent::__construct($name);

    # Cada "controller" debe especificar ruta/nombre de manifiesto
    $this->setXmlReference();

    # Cada "controller" debe especificar owner & parent que serán
    # heredados a los Componentes
    $this->setOwnerAndParent();

    # Injecta el manifiesto
    parent::injectXml();

    # Obtiene el objeto Request
    $this->request = OutgoingRequest::instance();
  }

  /**
   * Set XML Reference
   * Cada "controller" debe especificar el nombre del archivo XML
   * que contiene la estructura que será integrada al objeto.
   */
  abstract protected function setXmlReference();

  abstract protected function setOwnerAndParent();
  /**
   * Get
   * Permite que los "Components" sean accesibles como propiedades
   * públicas del objeto.
   */
  function __get($name)
  {
    if (array_key_exists($name, $this->components))
      return $this->components[$name];
  }

  /**
   * Attach Components
   * 
   */
  protected function attachComponents(XmlExtended $collection)
  {
    foreach ($this->namespaces as $url) {
      foreach ($collection->children($url) as $xml) {

        $elementName = $xml->getName();
        $name = $xml->getAttribute('name');

        if (empty($url)) {
          $componentName = "Atusan\\Components\\{$elementName}";
        } else {
          $nss = str_replace('.', '\\', str_replace('clr-namespace:', '', $url));
          $componentName = "\\$nss\\$elementName";
          $xml = $this->buildXML($xml);
        }
        $this->components[$name] = new $componentName($name, $xml, $this->owner, $this->parent);
      }
    }
  }

  /**
   * Build XML
   * Si la declaración del Elemento en el XML pertenece a un
   * nombre de espacios, entonces crea un objeto XML con la
   * declaración de los nombres de espacios y procesar la
   * integración.
   */
  protected function buildXML($xml): XmlExtended
  {
    $nss = [];
    foreach ($this->namespaces as $ns => $url) {
      if (empty($ns)) continue;
      $nss[] = "xmlns:{$ns}=\"{$url}\"";
    }

    $content = "<?xml version='1.0'?><Root ";
    $content .= implode(" ", $nss);
    $content .= ">" . $xml->asXML() . "</Root>";

    return simplexml_load_string($content, XmlExtended::class);
  }
}
