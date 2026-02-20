<?php

namespace Atusan\Controls;

class DataTreeControl extends DataViewControlBase
{
  protected $index;

  public function setIndex(int $index)
  {
    $this->index = $index;
  }

  public function getIndex()
  {
    return $this->index;
  }

  public function write()
  {
    $this->{$this->type}();
  }

  public function getId(): string
  {
    return "{$this->view->name}-{$this->index}-{$this->name}";
  }

  /**
   * Caret
   */
  protected function Caret()
  {
?>
    <td id="<?= $this->getId() ?>" class="caret" type="<?= strtolower($this->type) ?>"></td>
  <?php
  }
  /**
   * NoCaret
   */
  protected function NoCaret()
  {
  ?>
    <td id="<?= $this->getId() ?>" class="nocaret" type="<?= strtolower($this->type) ?>"></td>
  <?php
  }
  /**
   * Colspan
   */
  protected function Colspan()
  {
  ?>
    <td id="<?= $this->getId() ?>" colspan="<?= $this->xml->getAttribute('cols') ?>"></td>
  <?php
  }
  /**
   * Text
   */
  protected function Text()
  {
  ?>
    <td id="<?= $this->getId() ?>" class="tree-control" type="<?= strtolower($this->type) ?>"><?= $this->getValue() ?></td>
  <?php
  }
  /**
   * Check
   */
  protected function Check()
  {
  ?>
    <td id="<?= $this->getId() ?>" class="tree-control" type="<?= strtolower($this->type) ?>">
      <input type="checkbox" value="0" id="<?= $this->getId() ?>-input" name="<?= $this->getId() ?>-input" />
    </td>
  <?php
  }

  /**
   * ActionBar
   */
  protected function ActionBar()
  {
    $w = $this->xml->count() * 20;
  ?>
    <td id="<?= $this->getId() ?>" class="tree-control" type="<?= strtolower($this->type) ?>" style="width:<?= $w ?>px;">
      <?php
      foreach ($this->xml->children() as $action) {
      ?>
        <i type="action" class="<?= $action->getAttribute("icon") ?> action" id="<?= $this->getId() ?>-<?= $action->getAttribute('name') ?>"></i>
      <?php
      }
      ?>
    </td>
<?php
  }
}
