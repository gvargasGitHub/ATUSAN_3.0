<?= self::extend('header') ?>
<content>
  <?= self::$module->write() ?>
</content>
<?= self::extend('close') ?>