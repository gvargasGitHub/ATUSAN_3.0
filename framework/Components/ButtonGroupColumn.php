<?php

namespace Atusan\Components;

use Atusan\Components\ButtonGroupBase;

class ButtonGroupColumn extends ButtonGroupBase
{
  public function write()
  {
    include __DIR__ . DS . 'Views' . DS . 'buttongroup/column.view.php';
  }
}
