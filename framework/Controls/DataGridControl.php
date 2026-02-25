<?php

namespace Atusan\Controls;

use Atusan\Types\MenuOptionType;

class DataGridControl extends DataViewControlBase
{
  public function write()
  {
    match ($this->type) {
      'Hidden' => $this->InputHidden(),
      'ActionBar' => $this->ActionBar(),
      'Data' => $this->Data(),
      'CheckBox' => $this->InputCheck(),
      'Icon' => $this->IconStates(),
      'Menu' => $this->menuOptions(),
      'Switch' => $this->InputSwitch()
    };
  }

  public function getId(): string
  {
    return "{$this->view->name}-{$this->name}-{$this->row}";
  }

  protected function InputHidden()
  {
?>
    <Input type="hidden" id="<?= $this->getId() ?>" name="<?= $this->name ?>" value="<?= $this->getValue() ?>" />
  <?php
  }

  /**
   * Icon States
   */
  protected function IconStates()
  {
    $value = $this->getValue();
  ?>
    <td class="data" id="<?= $this->getId() ?>" type="state" <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      value="<?= $value ?>" style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>">
      <?php
      foreach ($this->xml->children() as $state) {
        if ($state->hasAttribute('resolve')) {
          $resolve = $state->getAttribute('resolve');
          if (!method_exists($this->view->owner, $resolve)) trigger_error("$resolve no existe en {$this->view->owner->name}", E_USER_ERROR);

          if (!$this->view->owner->$resolve($this->getData())) continue;
        } elseif ($value != $state->getAttribute('value')) continue;

        // Se re-define el ID de los estados: viewName-stateName-viewRow
      ?>
        <i id="<?= "{$this->view->name}-{$state->getAttribute('name')}-{$this->row}" ?>" type="state" class="state <?= $state->getAttribute('icon') ?>" title="<?= $state->getAttribute('title') ?>"
          style="<?= $state->buildPairs(';', ':', '', 'css') ?>"><?= $state->getAttribute('text') ?></i>
      <?php
      }
      ?>
    </td>
  <?php
  }

  /**
   * Action Bar
   */
  protected function ActionBar()
  {
  ?>
    <td class="data" id="<?= $this->getId() ?>" type="action" <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>">
      <?php
      foreach ($this->xml->children() as $action) {
        if ($action->hasAttribute('resolve')) {
          $resolve = $action->getAttribute('resolve');
          if (!method_exists($this->view->owner, $resolve)) trigger_error("$resolve no existe en {$this->view->owner->name}", E_USER_ERROR);

          if (!$this->view->owner->$resolve($this->getData())) continue;
        }
        // Se re-define el ID de las acciones: viewName-actionName-viewRow
      ?>
        <i id="<?= "{$this->view->name}-{$action->getAttribute('name')}-{$this->row}" ?>" type="action" class="action <?= $action->getAttribute('icon') ?>" title="<?= $action->getAttribute('title') ?>"
          style="<?= $action->buildPairs(';', ':', '', 'css') ?>"></i>
      <?php
      }
      ?>
    </td>
  <?php
  }

  protected function InputCheck()
  {
    $selected = ($this->getValue() == 1) ? 'checked' : '';
  ?>
    <td class="data" id="<?= $this->getId() ?>" type="checkbox"
      <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>">
      <Input type="checkbox" id="<?= $this->getId() ?>-input" name="<?= $this->name ?>" value="<?= $this->getValue() ?>" <?= $selected ?> />
    </td>
  <?php
  }

  protected function Data()
  {
  ?>
    <td class="data" id="<?= $this->getId() ?>" type="data"
      <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>"><?= $this->getValue() ?></td>
  <?php
  }

  protected function menuOptions()
  {
  ?>
    <td id="<?= $this->getId() ?>" type="menu" class="menu-options"
      <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="text-align:center;cursor:pointer;<?= $this->xml->buildPairs(';', ':', '', 'css') ?>">
      <i id="<?= $this->getId() ?>-menu" type="menu" class="fa fa-ellipsis-v"></i>
      <div class="content">
        <?php
        foreach ($this->xml->Options->children() as $opt) {
          $motype = $this->menuOptionType($opt)
        ?>
          <a class="option" onclick="(event) => event.preventDefault()" <?= $motype->buildPairs() ?>><?= $opt->getAttribute('text') ?></a>
        <?php
        }
        ?>
      </div>
    </td>
  <?php
  }

  public function menuOptionType($xml)
  {
    $name = ($xml->hasAttribute('name')) ? $xml->getAttribute('name') : "item{$this->index}";

    return new MenuOptionType($this->view->name, $name, $this->row);
  }

  protected function InputSwitch()
  {
    $selected = ($this->getValue() == 1) ? 'checked' : '';
  ?>
    <td class="data" id="<?= $this->getId() ?>" type="checkbox"
      <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>">
      <label class="switch" id="<?= $this->getId() ?>-switch">
        <Input type="checkbox" class="changeEv" id="<?= $this->getId() ?>-input" name="<?= $this->name ?>" value="<?= $this->getValue() ?>" <?= $selected ?> />
        <Span class="slider" id="<?= $this->getId() ?>-slider"></Span>
      </label>
    </td>
<?php
  }
}
