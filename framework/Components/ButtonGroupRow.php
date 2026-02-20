<?php

namespace Atusan\Components;

use Atusan\Components\ButtonGroupBase;

class ButtonGroupRow extends ButtonGroupBase
{
  public function write()
  {
    $class = ['ats-btn-group-row'];
    $class[] = ($this->xml->hasAttribute('align'))
      ? "ats-btn-align-" . $this->xml->getAttribute('align')
      : "ats-btn-align-left";

    include __DIR__ . DS . 'Views' . DS . 'buttongroup/row.view.php';
  }
}
