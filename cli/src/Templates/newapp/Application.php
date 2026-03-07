<?php

namespace App;

use Atusan\Application\Application as Controller;


class Application extends Controller
{
  protected array $css = [
    'base.css',
    'colors.css'
  ];

  protected array $js = [
    'app.js'
  ];

  public function onInit()
  {
    $this->loadDotEnv();
  }

  public function onOpen()
  {
    // TODO
  }

  public function onClose()
  {
    // TODO
  }
}
