<?php

namespace Atusan\Components;

class DataForm extends DataViewBase
{
  /**
   * 
   */
  protected function setDataViewType()
  {
    $this->type = 'DataForm';
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
    $this->catalog = [
      'ButtonGroupBar' => "Atusan\\Components\\",
      'ButtonGroupColumn' => "Atusan\\Components\\",
      'ButtonGroupRow' => "Atusan\\Components\\",
      'TabGroup' => "Atusan\\Components\\"
    ];
  }
  /**
   * 
   */
  protected function loadControls()
  {
    $this->controls = $this->attachControls($this->xml);
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
    if (!property_exists($this, 'route')) $this->route = $_SERVER['REQUEST_URI'];

    include __DIR__ . DS . 'Views' . DS . strtolower($this->type) . '/view.php';
  }

  /**
   * 
   */
  public function writeControl(string $name)
  {
    if (count($this->data) == 0)
      $this->data[0] = [];

    foreach ($this->controls as $control) {
      if ($control->name == $name) {
        if ($control->category == 'Control') {
          $control->object->setData($this->data[0]);
          $control->object->write();
        } else
          $control->object->write();

        return;
      }
    }
    trigger_error("El control {$name} no existe para {$this->name}", E_USER_WARNING);
  }

  /**
   * 
   */
  public function getItem(string $name)
  {
    return $this->data[0][$name];
  }

  /**
   * 
   */
  public function setItem(string $name, mixed $value)
  {
    $this->data[0][$name] = $value;
  }

  public function typeControl(string $name, string $type)
  {
    if (count($this->data) == 0)
      $this->data[0] = [];

    foreach ($this->controls as $control) {
      if ($control->name == $name) {
        if ($control->category == 'Control') $control->object->setType($type);
        return;
      }
    }
    trigger_error("El control {$name} no existe para {$this->name}", E_USER_WARNING);
  }

  public function enableControl(string $name, bool $enable)
  {
    if (count($this->data) == 0)
      $this->data[0] = [];

    foreach ($this->controls as $control) {
      if ($control->name == $name) {
        if ($control->category == 'Control') $control->object->setEnable($enable);
        return;
      }
    }
    trigger_error("El control {$name} no existe para {$this->name}", E_USER_WARNING);
  }
}
