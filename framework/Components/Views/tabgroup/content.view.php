<?php
include 'content-begin.view.php';

if (property_exists($this, 'view'))
  include $this->owner->directory . DS . "{$this->view}.view.php";
else {
  foreach ($this->components as $component) {
    if ($component instanceof Atusan\Module\ModuleInterface)
      if ($component instanceof Atusan\Module\NestedModule)
        $component->nested();
      else
        trigger_error("{$component->name} debe heredar de NestedModule", E_USER_ERROR);
    else
      $component->write();
  }
}

include 'content-end.view.php';
