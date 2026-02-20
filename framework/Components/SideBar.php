<?php

namespace Atusan\Components;

use Atusan\Components\ComponentBase;
use Atusan\Types\MenuItemType;

class SideBar extends ComponentBase
{
  protected $index = 0;

  /**
   * 
   */
  protected function postConstruct() {}

  public function write()
  {
    if (!property_exists($this, 'title')) $this->title = "";
    include __DIR__ . DS . 'Views' . DS . 'sidebar/view.php';
  }

  public function editItem($itemName, $attributeName, $attributeNewValue)
  {
    $item = $this->seekRecursive($this->xml, $itemName);

    if ($item) {
      $item->setAttribute($attributeName, $attributeNewValue);
    }
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  protected function itemType($xml)
  {
    $this->index++;
    $name = ($xml->hasAttribute('name')) ? $xml->getAttribute('name') : "item{$this->index}";
    $text = ($xml->hasAttribute('text')) ? $xml->getAttribute('text') : '';
    $icon = ($xml->hasAttribute('icon')) ? $xml->getAttribute('icon') : '';
    $module = ($xml->hasAttribute('module')) ? $xml->getAttribute('module') : '';
    $href = ($xml->hasAttribute('href')) ? $xml->getAttribute('href') : '';

    return new MenuItemType($this->name, $name, $text, $icon, $module, $href);
  }

  protected function getNSBlocks()
  {
    $namespaces = array_merge($this->owner->namespaces, $this->xml->getDocNamespaces());
    foreach ($namespaces as $ns => $url) {
      if (!preg_match('/^clr-namespace:/', $url)) continue;
      foreach ($this->xml->children($ns, 1) as $xml) {
        return $xml;
      }
    }
  }

  protected function getBlocks()
  {
    return (get_class($this) == 'Atusan\\Components\\SideBar')
      ? $this->xml
      : $this->getNSBlocks();
  }

  protected function seekRecursive($item, $name)
  {
    if ($name == $item->getAttribute('name')) return $item;

    foreach ($item->children() as $child) {
      $res = $this->seekRecursive($child, $name);

      if ($res) return $res;
    }

    return false;
  }
}
