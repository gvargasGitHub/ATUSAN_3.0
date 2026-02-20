<?php

namespace Atusan\Components;

use Atusan\Controller\OwnerBase;

class Panel extends OwnerBase
{

  protected $dictionary = [];

  function __construct($name, protected $xmlRef, protected $owner, protected $parent)
  {
    parent::__construct($name, $owner->directory);

    $this->setCollection();

    $this->loadComponents();

    $this->dictionary();
  }

  protected function setXmlReference() {}
  /** */
  protected function setOwnerAndParent()
  {
    $this->parent = $this;
  }
  /**
   * 
   */
  protected function setCollection()
  {
    $this->collection['Left'] = $this->xml->Left;
    $this->collection['Content'] = $this->xml->Content;
    $this->collection['Right'] = $this->xml->Right;
  }
  /**
   * 
   */
  protected function loadComponents()
  {
    $this->attachComponents($this->collection['Left']);
    $this->attachComponents($this->collection['Content']);
    $this->attachComponents($this->collection['Right']);
  }

  protected function dictionary()
  {
    foreach ($this->collection as $panel => $xml) {
      $this->dictionary[$panel] = [];
      foreach ($this->namespaces as $url) {
        foreach ($xml->children($url) as $xml) {
          $this->dictionary[$panel][] = $xml->getAttribute('name');
        }
      }
    }
  }

  public function writePanel($position)
  {
    if (property_exists($this->collection[$position], 'view'))
      include $this->owner->directory . DS . "{$this->collection->Left->view}.php";
    else {
      foreach ($this->dictionary[$position] as $name) $this->components[$name]->write();
    }
  }

  public function write()
  {
    include __DIR__ . DS . 'Views' . DS . 'panel/view.php';
  }

  public function beginWrapper()
  {
    include __DIR__ . DS . 'Views' . DS . 'panel/begin.view.php';
  }

  public function beginPanel()
  {
    include __DIR__ . DS . 'Views' . DS . 'panel/begin.panel.view.php';
  }

  public function end()
  {
    include __DIR__ . DS . 'Views' . DS . 'panel/end.panel.view.php';
  }
  public function endWrapper()
  {
    include __DIR__ . DS . 'Views' . DS . 'panel/end.panel.view.php';
  }
}
