<?= self::extend('header') ?>
<?= self::$module->topbar->write() ?>
<content>
  <?= self::$module->write() ?>
</content>
<?= self::extend('close') ?>