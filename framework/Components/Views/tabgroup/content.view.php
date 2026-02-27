<?php

use Atusan\FileSystem\FileSystem;

include 'content-begin.view.php';

if (property_exists($this, 'view')) {
  if (file_exists($this->owner->directory . DS . "{$this->view}.php"))
    include $this->owner->directory . DS . "{$this->view}.php";
  else {
    $located = FileSystem::locateFile(APP_DIRECTORY, basename($this->view), 'php');
    if (!$located) trigger_error("La vista {$this->view} no existe", E_USER_ERROR);

    include $located[0];
  }
} else {
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
