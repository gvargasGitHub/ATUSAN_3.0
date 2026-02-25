<?php

namespace Atusan\Application;

use Atusan\Controller\OwnerBase;
use Dotenv\Dotenv;

abstract class Application extends OwnerBase implements ApplicationInterface
{
  /**
   * Load Dot Env
   */
  public function loadDotEnv()
  {
    $dotenv = Dotenv::createImmutable($this->directory);
    $dotenv->safeLoad();
  }
  /** */
  protected function setOwnerAndParent() {}
  /**
   * Set Manifest References
   */
  protected function setXmlReference()
  {
    # El archivo XML de una Aplicación siempre será "manifest.xml"
    $this->xmlRef = $this->directory . '/manifest.xml';
  }

  const FONT_RESOURCES =
  '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />';

  const JS_RESOURCES = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>';

  public function cssResources()
  {
    $dirs = [
      'reset.css',
      'flexbox.css',
      'alerts.css',
      'buttons.css',
      'dataform.css',
      'datagrid.css',
      'datatree.css',
      'loader.css',
      'modal.css',
      'navbar.css',
      'panels.css',
      'tabgroup.css',
      'sidebar.css'
    ];
    $content = '';
    foreach ($dirs as $css)
      $content .= file_get_contents(APP_ROOT . '\\framework\\Statics\\css\\' . $css) . "\n";

    foreach ($this->css as $css)
      $content .= file_get_contents(APP_ROOT . '\\public\\css\\' . $css) . "\n";

    return "<style>\n{$content}\n</style>";
  }

  public function jsResources()
  {
    $dirs = [
      'atusan.js',
      'controls.js',
      'components.js',
      'owners.js'
    ];
    $content = '';
    foreach ($dirs as $js)
      $content .= file_get_contents(APP_ROOT . '\\framework\\Statics\\js\\' . $js) . "\n";

    foreach ($this->js as $js)
      $content .= file_get_contents(APP_ROOT . '\\public\\js\\' . $js) . "\n";

    return "<script>\n{$content}\n</script>";
  }
}
