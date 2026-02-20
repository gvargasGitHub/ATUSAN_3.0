<?php

namespace Atusan\Components;

use Atusan\Controller\ControllerBase;

abstract class ComponentBase extends ControllerBase implements ComponentInterface
{
  /**
   * Controller
   */
  function __construct($name, protected $xmlRef, public $owner, public $parent)
  {
    parent::__construct($name);
    # Injecta el manifiesto
    parent::injectXml();

    # Ejecuta post Construct del Componente
    $this->postConstruct();
  }

  /**
   * 
   */
  abstract protected function postConstruct();
}
