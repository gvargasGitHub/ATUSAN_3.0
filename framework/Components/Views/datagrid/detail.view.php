<?php
# Recorre colección de datos para integrar fila por fila.
$nofRows = count($this->data);
for ($row = 0; $row < $nofRows; $row++) {
  foreach ($this->controls['Tbody'] as $section) {
?>
    <tr class="row">
      <?php
      foreach ($section as $control) {
        if ($control->category == 'Control') {
          $control->object->setData($this->data[$row]);
          $control->object->setRow($row);
          $control->object->write();
        } else
          $control->object->write();
      }
      ?>
    </tr>
<?php
  }
}
