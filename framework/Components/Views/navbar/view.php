<script>
  var <?= $this->name ?> = new NavBar("<?= $this->name ?>", "<?= $this->owner->name ?>");
</script>
<nav>
  <div id="<?= $this->name ?>" class="ats-navbar">
    <span class="title"><?= $this->title ?></span>
    <?php
    $blocks = $this->getBlocks();
    foreach ($blocks->children() as $block) {
    ?>
      <Ul class="<?= strtolower($block->getName()) ?>">
        <?php
        # Recorre los items declarados
        foreach ($block->children() as $xml) {
          $item = $this->itemType($xml);
          if ($xml->count() > 0) {
        ?>
            <Li class="dropdown">
              <?= $item->text ?><i class="caret fa fa-caret-down"></i>
              <Div class="content">
                <ul>
                  <?php
                  foreach ($xml->children() as $ch) {
                    $chitem = $this->itemType($ch);
                  ?>
                    <li class="item" <?= $chitem->buildPairs() ?>><i class="icon <?= $chitem->icon ?>"></i><?= $chitem->text ?></li>
                  <?php
                  }
                  ?>
                </ul>
              </Div>
            </Li>
          <?php
          } else {
          ?>
            <Li class="item" <?= $item->buildPairs() ?>><i class="icon <?= $item->icon ?>"></i><?= $item->text ?></Li>
        <?php
          }
        }
        ?>
      </Ul>
    <?php
    }
    ?>
    <Div class="bars" ats-owner="<?= $this->name ?>">
      <div class="bar bar1"></div>
      <div class="bar bar2"></div>
      <div class="bar bar3"></div>
    </Div>
  </div>
</nav>