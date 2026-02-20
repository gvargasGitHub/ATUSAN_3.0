<?php

namespace Atusan\Components;

use Atusan\Controller\OwnerBase;

class Modal extends OwnerBase
{
  function __construct($name, protected $xmlRef, protected $owner)
  {
    parent::__construct($name, $owner->directory);

    $this->setCollection();

    $this->loadComponents();
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
    $this->collection = $this->xml;
  }
  /**
   * 
   */
  protected function loadComponents()
  {
    $this->attachComponents($this->collection);
  }

  public function write()
  {
    if (!property_exists($this, 'title')) $this->title = '';
    if (!property_exists($this, 'footer')) $this->footer = '';

    include __DIR__ . DS . 'Views' . DS . 'modal/view.php';
  }
}
