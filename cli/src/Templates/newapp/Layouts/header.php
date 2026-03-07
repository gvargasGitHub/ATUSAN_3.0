<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= self::$app->title ?></title>

  <!-- Framework FONTS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Framework CSS -->
  <?= self::$app->cssResources() ?>

  <!-- Framework JS -->
  <?= self::$app::JS_RESOURCES ?>
  <?= self::$app->jsResources() ?>
</head>

<body>
  <div class="ats-loader-overlay" id="ats-loader">
    <div class="ats-loader-indicator"></div>
  </div>

  <?= self::$module->jsDeclare() ?>