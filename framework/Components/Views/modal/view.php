<?php

use Atusan\FileSystem\FileSystem;
?>
<script>
  var <?= $this->name ?> = new Modal("<?= $this->name ?>", "<?= $this->owner->name ?>");
</script>
<Div id="<?= $this->name ?>" class="ats-modal">
  <Div class="content">
    <div class="header"><?= $this->title ?><i ats-owner="<?= $this->name ?>" class="close">&times;</i></div>
    <div class="body">
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
        foreach ($this->components as $component) $component->write();
      }
      ?>
    </div>
    <div class="footer"><?= $this->footer ?></div>
  </Div>
</Div>