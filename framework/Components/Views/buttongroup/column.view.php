<Div id="" class="ats-btn-group-column">
  <?php
  foreach ($this->xml->children() as $xml) {
    [
      'name' => $name,
      'text' => $text,
      'icon' => $icon,
      'click' => $click
    ] = $this->getCommonProperties($xml);
  ?>
    <Button id="<?= $name ?>" class="ats-btn" <?= $click ?> <?= $xml->buildAttributesPairs('html') ?>>
      <?= $icon, $text ?>
    </Button>
  <?php
  }
  ?>
</Div>