<?php

namespace Atusan\Components;

use Atusan\Components\ButtonGroupBase;

class ButtonGroupBar extends ButtonGroupBase
{
  public function write()
  {
    $percent = 100 / $this->xml->count();

    include __DIR__ . DS . 'Views' . DS . 'buttongroup/bar.view.php';
  }
}
