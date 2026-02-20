<?php

namespace Atusan\Components;

class DataGrid extends DataViewBase
{
  /**
   * 
   */
  protected function setDataViewType()
  {
    $this->type = 'DataGrid';
  }

  /**
   * Init DataView Properties
   */
  protected function initDataViewProperties() {}

  /**
   * 
   */
  protected function setCatalog()
  {
    $this->catalog = [];
  }

  protected function loadControls()
  {
    $this->controls = [
      'Thead' => [],
      'Tbody' => [],
      'Tsummary' => [],
      'Tfoot' => [],
    ];

    foreach ($this->xml->children() as $xml) {
      $name = $xml->getName();

      if (!array_key_exists($name, $this->controls))
        trigger_error("{$name} no es una sección válida para DataGrid", E_USER_ERROR);

      array_push(
        $this->controls[$name],
        $this->attachControls($xml)
      );
    }
  }

  /**
   * Build
   */
  public function build(): string
  {
    ob_start();

    include __DIR__ . DS . 'Views' . DS . strtolower($this->type) . '/body.view.php';

    $html = ob_get_contents();

    ob_clean();

    return $html;
  }

  /**
   * 
   */
  public function write()
  {
    if (!property_exists($this, 'title')) $this->title = '';
    if (!property_exists($this, 'footer')) $this->footer = '';

    include __DIR__ . DS . 'Views' . DS . strtolower($this->type) . '/view.php';
  }
}
