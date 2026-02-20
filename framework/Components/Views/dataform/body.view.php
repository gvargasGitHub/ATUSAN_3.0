<?php if (!property_exists($this, 'route')) $this->route = $_SERVER['REQUEST_URI']; ?>
<form id="<?= $this->name ?>-form" ats-route="<?= $this->route ?>">
  <?php
  if (property_exists($this, 'view'))
    include $this->owner->directory . DS . "{$this->view}.php";
  else {
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