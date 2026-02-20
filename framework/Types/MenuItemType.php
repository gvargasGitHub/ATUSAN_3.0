<?php

namespace Atusan\Types;

class MenuItemType
{
  function __construct(public $owner, public $name, public $text, public $icon, public $module, public $href) {}

  public function buildPairs()
  {
    $output = [
      'ats-owner="' . $this->owner . '"',
      'ats-name="' . $this->name . '"',
      'ats-module="' . $this->module . '"'
    ];

    return implode(' ', $output);
  }
}
