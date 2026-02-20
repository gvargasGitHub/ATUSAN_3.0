<?php

namespace Atusan\Components;

class TabGroup extends ComponentBase
{
  protected $contents = [];
  /**
   * 
   */
  protected function postConstruct()
  {
    foreach ($this->xml->children() as $xml) {
      $name = $xml->getAttribute('name');

      $this->contents[$name] = new TabGroupContent($name, $xml, $this->owner, $this);
    }
  }

  /**
   * Get
   * Permite que los "Contents" sean accesibles como propierdades
   * públicas del objeto.
   */
  function __get($name)
  {
    if (array_key_exists($name, $this->contents))
      return $this->contents[$name];
  }

  public function write()
  {
    include __DIR__ . DS . 'Views' . DS . 'tabgroup/view.php';
  }
}
