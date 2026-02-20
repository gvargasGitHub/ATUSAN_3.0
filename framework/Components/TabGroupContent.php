<?php

namespace Atusan\Components;

use Atusan\Controller\OwnerBase;
use Atusan\Types\TabGroupButtonType;

class TabGroupContent extends OwnerBase
{
  public $closeable = false;

  function __construct($name, protected $xmlRef, protected $owner, protected $parent)
  {
    parent::__construct($name, $owner->directory);

    $this->setCollection();

    $this->loadComponents();

    $this->closeable = property_exists($this, 'closeable');
  }

  protected function setXmlReference() {}
  /** */
  protected function setOwnerAndParent() {}
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
    include __DIR__ . DS . 'Views' . DS . 'tabgroup/content.view.php';
  }

  public function begin()
  {
    include __DIR__ . DS . 'Views' . DS . 'tabgroup/content-begin.view.php';
  }

  public function end()
  {
    include __DIR__ . DS . 'Views' . DS . 'tabgroup/content-end.view.php';
  }

  public function button()
  {
    include __DIR__ . DS . 'Views' . DS . 'tabgroup/button.view.php';
  }

  protected function itemType()
  {
    $name = $this->xml->getAttribute('name');
    $text = $this->xml->getAttribute('text');
    $icon = ($this->xml->hasAttribute('icon')) ? $this->xml->getAttribute('icon') : '';
    $module = ($this->xml->hasAttribute('module')) ? $this->xml->getAttribute('module') : '';

    return new TabGroupButtonType($this->owner->name, $this->parent->name, $name, $text, $icon, $module);
  }
}
