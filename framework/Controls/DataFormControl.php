<?php

namespace Atusan\Controls;

class DataFormControl extends DataViewControlBase
{
  public function write()
  {
    match ($this->type) {
      'AutoComplete' => $this->AutoComplete(),
      'Csrf' => $this->Csrf(),
      'Data' => $this->Data(),
      'Check' => $this->InputCheck(),
      'Date' => $this->InputBasic(),
      'Time' => $this->InputBasic(),
      'File' => $this->InputFile(),
      'Hidden' => $this->InputHidden(),
      'Text' => $this->InputBasic(),
      'TextArea' => $this->InputTextArea(),
      'Password' => $this->InputBasic(),
      'Radio' => $this->inputRadio(),
      'Switch' => $this->inputSwitch(),
      'Select' => $this->inputSelect()
    };
  }

  public function getId(): string
  {
    return "{$this->view->name}-{$this->name}";
  }

  public function getName(): string
  {
    return $this->name;
  }

  protected function Csrf()
  {
?>
    <Input type="hidden" id="<?= $this->view->name ?>-csrf" name="csrf" value="<?= $this->getValue() ?>" />
  <?php
  }

  protected function InputHidden()
  {
  ?>
    <Input type="hidden" id="<?= $this->getId() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>" />
    <?php
  }

  protected function Data()
  {
    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->getId() ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label>
    <?php
    }
    ?>
    <Input type="text" id="<?= $this->getId() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"
      class="form-control" readonly="readonly" <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>" />
    <?php
  }

  protected function AutoComplete()
  {
    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->getId() ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label>
    <?php
    }
    ?>
    <Div class="autocomplete">
      <Input type="text" id="<?= $this->getId() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"
        class="form-control inputEv keydownEv autocomplete-control" <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
        style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>" autocomplete="off" />
      <Div class="autocomplete-list"></Div>
    </Div>
    <?php
  }

  protected function InputCheck()
  {
    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->getId() ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label><br />
    <?php
    }
    $selected = ($this->getValue() == 1) ? 'checked' : '';
    ?>
    <Input type="checkbox" id="<?= $this->getId() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"
      class="changeEv" <?= $selected ?> <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>" /><br />
    <?php
  }

  protected function InputTextArea()
  {
    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->getId() ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label>
    <?php
    }
    ?>
    <TextArea type="text" id="<?= $this->getId() ?>" name="<?= $this->getName() ?>"
      class="form-control inputEv" <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>"><?= $this->getValue() ?></TextArea>
    <?php
  }

  protected function InputBasic()
  {
    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->getId() ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label>
    <?php
    }
    ?>
    <Input type="<?= strtolower($this->type) ?>" id="<?= $this->getId() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"
      class="form-control inputEv" <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>" />
    <?php
  }

  protected function InputFile()
  {
    $text = ($this->xml->hasAttribute('text'))
      ? $this->xml->getAttribute('text')
      : 'Elige un archivo';

    $icon = ($this->xml->hasAttribute('icon'))
      ? $this->xml->getAttribute('icon')
      : 'fa fa-upload';

    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->getId() ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label>
    <?php
    }
    ?>
    <Input type="file" class="file changeEv" id="<?= $this->getId() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>" multiple />
    <Label id="<?= $this->getId() ?>-label" class="form-control" for="<?= $this->getId() ?>"><i class="<?= $icon ?>"></i><?= $text ?></Label>
    <ul id="<?= $this->getId() ?>-ul"></ul>
    <label class="file-foot">Tamaño máximo permitido: <?= ini_get('upload_max_filesize') ?></label><br />
    <?php
  }

  protected function inputRadio()
  {
    $display = ($this->xml->hasAttribute('display')) ? $this->xml->getAttribute('display') : 'block';
    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->name ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label><br />
    <?php
    }
    $index = 0;
    foreach ($this->xml->children() as $opt) {
      $index += 1;
      $selected = ($this->getValue() == $opt->getAttribute('value')) ? 'checked' : '';
    ?>
      <div class="radiobox <?= $display ?>">
        <Input type="radio" class="radio changeEv" id="<?= $this->getId() ?>-<?= $index ?>" name="<?= $this->getName() ?>" value="<?= $opt->getAttribute('value') ?>" <?= $selected ?> />
        <label class="label" for="<?= $this->getId() ?>-<?= $index ?>"><?= $opt->getAttribute('text') ?></label>
      </div>
    <?php
    }
    ?>
    <br />
    <?php
  }

  protected function inputSelect()
  {
    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->getId() ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label>
    <?php
    }
    ?>
    <Select id="<?= $this->getId() ?>" type="select" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"
      class="form-control changeEv" <?= $this->xml->buildPairs(' ', '=', '"', 'html') ?>
      style="<?= $this->xml->buildPairs(';', ':', '', 'css') ?>">
      <?php
      foreach ($this->xml->children() as $opt) {
        $selected = ($this->getValue() == $opt->getAttribute('value')) ? 'selected' : '';
      ?>
        <option value="<?= $opt->getAttribute('value') ?>" <?= $selected ?>><?= $opt->getAttribute('text') ?></option>
      <?php
      }
      ?>
    </Select>
    <?php
  }

  protected function InputSwitch()
  {
    if ($this->xml->hasAttribute('label')) {
    ?>
      <Label for="<?= $this->getId() ?>" class="form-label"><?= $this->xml->getAttribute('label') ?></Label>
    <?php
    }
    $selected = ($this->getValue() == 1) ? 'checked' : '';
    ?>
    <label class="switch">
      <Input type="checkbox" class="changeEv" <?= $selected ?> id="<?= $this->getId() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>" />
      <Span class="slider"></Span>
    </label>
<?php
  }
}
