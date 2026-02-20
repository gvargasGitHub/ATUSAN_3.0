<?php

namespace Atusan\Components;

use Atusan\Types\DataViewControlType;
use Atusan\Xml\XmlExtended;

abstract class DataViewBase extends ComponentBase
{
  protected $type;

  protected $data = [];

  protected $controls = [];

  protected $index = 0;

  protected $catalog = [];

  /**
   * 
   */
  protected function postConstruct()
  {
    $this->setDataViewType();

    $this->initDataViewProperties();

    $this->setCatalog();

    $this->loadControls();
  }

  /**
   * 
   */
  abstract protected function setDataViewType();

  /**
   * 
   */
  abstract protected function initDataViewProperties();
  /**
   * 
   */
  abstract protected function setCatalog();
  /**
   * 
   */
  abstract protected function loadControls();

  /**
   * Build
   */
  abstract public function build(): string;

  /**
   * 
   */
  public function rowsCount(): int
  {
    return count($this->data);
  }
  /**
   * Import
   */
  public function import(array $data): int
  {
    $this->data = (is_array($data) && array_diff_key($data, array_keys($data)))
      ? [$data] : $data;

    return count($this->data);
  }

  protected function getView(): string
  {
    return __DIR__ . DS . 'Views' . DS . strtolower($this->type) . '.view.php';
  }


  /**
   * Attach Controls
   */
  protected function attachControls(XmlExtended $collection): array
  {
    $target = [];
    foreach ($this->namespaces as $url) {
      foreach ($collection->children($url) as $xml) {
        $this->index += 1;

        $elementName = $xml->getName();

        if ($elementName == 'ControlCsrf') $xml->setAttribute('name', 'csrf');

        $name = $xml->hasAttribute('name') ? $xml->getAttribute('name') : "{$elementName}{$this->index}";

        if (empty($url)) {
          # Controles, Componentes
          if (preg_match('/(^Control$|^Control)/', $elementName)) {
            $controlClass = "Atusan\\Controls\\{$this->type}Control";

            $object = new $controlClass($name, $xml, $this);
            $category = 'Control';
          } elseif (array_key_exists($elementName, $this->catalog)) {
            $componentName = "{$this->catalog[$elementName]}{$elementName}";

            $object = new $componentName($name, $xml, $this->owner, $this);
            $category = 'Component';
          } else {
            $object = $xml;
            $category = 'XmlExtend';
          }
        } else {
          # Componente Personalizado
          $nss = str_replace('.', '\\', str_replace('clr-namespaces:', '', $url));
          $componentName = "\\$nss\\$elementName";
          $object = new $componentName($name, $xml, $this->owner, $this);
          $category = 'Component';
        }

        $target[$name] = new DataViewControlType($category, $name, $object);
      }
    }

    return $target;
  }
}
