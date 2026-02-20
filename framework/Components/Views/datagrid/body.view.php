<table id="<?= $this->name ?>-table">
  <thead>
    <?php
    foreach ($this->controls['Thead'] as $section) {
    ?>
      <tr>
        <?php
        foreach ($section as $control) {
          if ($control->category == 'Control')
            $control->object->write();
          else
            $control->object->write();
        }
        ?>
      </tr>
    <?php
    }
    ?>
  </thead>
  <tbody class="detail">
    <?php include 'detail.view.php' ?>
  </tbody>
  <tbody class="summary"></tbody>
  <tfoot>
    <?php
    foreach ($this->controls['Tfoot'] as $section) {
    ?>
      <tr>
        <?php
        foreach ($section as $control) {
          if ($control->category == 'Control')
            $control->object->write();
          else
            $control->object->write();
        }
        ?>
      </tr>
    <?php
    }
    ?>
  </tfoot>
</table>