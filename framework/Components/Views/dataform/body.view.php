<?php

use Atusan\FileSystem\FileSystem;

if (!property_exists($this, 'route')) $this->route = $_SERVER['REQUEST_URI']; ?>
<form id="<?= $this->name ?>-form" ats-route="<?= $this->route ?>">
  <?php
  if (property_exists($this, 'view')) {
    if (file_exists($this->owner->directory . DS . "{$this->view}.php"))
      include $this->owner->directory . DS . "{$this->view}.php";
    else {
      $located = FileSystem::locateFile(APP_DIRECTORY, basename($this->view), 'php');
      if (!$located) trigger_error("La vista {$this->view} no existe", E_USER_ERROR);

      include $located[0];
    }
  } else {
    if (count($this->data) == 0) $this->data[0] = [];

    foreach ($this->controls as $control) {
      if ($control->category == 'Control') {
        $control->object->setData($this->data[0]);
        $control->object->write();
      } else
        $control->object->write();
    }
  }
  ?>
</form>