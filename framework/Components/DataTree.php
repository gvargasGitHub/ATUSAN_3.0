<?php

namespace Atusan\Components;

use Atusan\Types\DataTreeDataType;

class DataTree extends DataViewBase
{
  protected $numOfLevels = 0;
  protected $levelsBound = -1;
  protected $numOfItems = 0;

  /**
   * 
   */
  protected function setDataViewType()
  {
    $this->type = 'DataTree';
  }

  /**
   * 
   */
  protected function initDataViewProperties()
  {
    $this->numOfLevels = count($this->xml->Level);
    $this->levelsBound = $this->numOfLevels - 1;
    $this->numOfItems = 0;
  }
  /**
   * 
   */
  protected function setCatalog()
  {
    $this->catalog = [];
  }

  protected function loadControls()
  {
    $l = 0;
    foreach ($this->xml->children() as $xmlLevel) {
      $this->controls[$l] = [];
      $r = 0;
      foreach ($xmlLevel->Row as $xmlRow) {
        $this->controls[$l][$r] = [];
        array_push($this->controls[$l][$r], $this->attachControls($xmlRow));
        $r++;
      }
      $l++;
    }
  }

  /**
   * Build
   */
  public function build(): string
  {
    ob_start();

    include __DIR__ . DS . 'Views' . DS . strtolower($this->type) . '/body.view.php';

    $html = ob_get_contents();

    ob_clean();

    return $html;
  }

  /**
   * Write
   */
  public function write()
  {
    if (!property_exists($this, 'title')) $this->title = '';
    if (!property_exists($this, 'footer')) $this->footer = '';

    include __DIR__ . DS . 'Views' . DS . strtolower($this->type) . '/view.php';
  }

  public function retrieve()
  {
    $this->data = $this->recursive(0, func_get_args());
  }

  protected function recursive(int $level, array $args = [])
  {
    if ($level > $this->levelsBound) return [];

    $data = [];
    $model = $this->xml->Level[$level]->getAttribute('model');

    $modelResults = call_user_func_array($model, $args);

    for ($i = 0; $i < count($modelResults); $i++) {
      $this->numOfItems += 1;
      $type = new DataTreeDataType($this->numOfItems, $modelResults[$i]);
      $type->children = $this->recursive(($level + 1), array_values($modelResults[$i]));

      $data[] = $type;
    }

    return $data;
  }

  protected function writeBodyRecursive(array $data, int $level)
  {
    foreach ($data as $type) {
?>
      <li id="<?= $this->name ?>-<?= $type->index ?>" <?= $this->buildDataPairs($type->data) ?>>
        <table id="<?= $this->name ?>-<?= $type->index ?>-table" class="item">
          <tbody>
            <?php
            foreach ($this->controls[$level] as $container) {
              foreach ($container as $row) {
            ?>
                <tr>
                  <?php
                  foreach ($row as $control) {
                    $control->object->setData($type->data);
                    $control->object->setIndex($type->index);
                    $control->object->write();
                  }
                  ?>
                </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
        <?php
        if ($type->hasChildren()) {
        ?>
          <ul id="<?= $this->name ?>-<?= $type->index ?>-content" class="content">
            <?php
            $this->writeBodyRecursive($type->children, $level + 1);
            ?>
          </ul>
        <?php
        }
        ?>
      </li>
<?php
    }
  }

  protected function buildDataPairs($data): string
  {
    $output = [];
    foreach ($data as $k => $v)
      $output[] = "data-{$k}=\"{$v}\"";

    return implode(' ', $output);
  }
}
