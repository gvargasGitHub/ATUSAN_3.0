<?php

namespace Atusan\Module;

use Atusan\Controller\OwnerBase;
use Atusan\Response\Response;
use Atusan\Xml\XmlLoader;
use Atusan\Xml\XmlValidator;
use Dotenv\Dotenv;
use ReflectionClass;

abstract class ModuleBase extends OwnerBase implements ModuleInterface
{
  function __construct(public $app, public $uri)
  {
    $r = new ReflectionClass(get_class($this));

    parent::__construct(basename(get_class($this)), dirname($r->getFileName()));

    $this->response = new Response($this);

    $this->setCollection();
  }
  /**
   * 
   */
  protected function setXmlReference()
  {
    # El archivo XML de un Módulo puede tener las siguiente nomenclatura:
    //  a) module-directory/{module-name}.xml
    //  b) module-directory/Components.xml
    if (XmlValidator::fileExists($this->directory . "/{$this->name}.xml"))
      $this->xmlRef = $this->directory . "/{$this->name}.xml";
    elseif (XmlValidator::fileExists($this->directory . "/Components.xml"))
      $this->xmlRef = $this->directory . "/Components.xml";
  }

  /** */
  protected function setOwnerAndParent()
  {
    $this->owner = $this;
    $this->parent = $this;
  }
  /**
   * setCollection
   */
  protected function setCollection()
  {
    // Se establece Root como el contenedor de los Componentes del módulo
    $this->collection['module'] = $this->xml;

    // Se construye la ruta al archivo Template.xml
    $templateDir = APP_DIRECTORY . DS . $this->template . DS . basename($this->template) . ".xml";

    // Se obtiene el archivo Template.xml declarado o un template vacio
    $xmlTemplate = XmlValidator::fileExists($templateDir) ? XmlLoader::load($templateDir) : XmlLoader::empty();

    # Actualiza "namespaces"
    $this->namespaces = array_merge(
      $this->namespaces,
      $xmlTemplate->getDocNamespaces(true, true)
    );
    // Se establece Root como el contenedor de los Componentes declarados
    // en la template
    $this->collection['template'] = $xmlTemplate;
  }

  /**
   * Load Dot Env
   */
  public function loadDotEnv()
  {
    $dotenv = Dotenv::createImmutable(APP_DIRECTORY);
    $dotenv->safeLoad();
  }

  /**
   * 
   */
  public function loadComponents()
  {
    parent::attachComponents($this->collection['module']);
    parent::attachComponents($this->collection['template']);
  }

  /**
   * JS Declare
   * Se recomienda invocar este método después de 
   */
  public function jsDeclare(bool $isNested = false): string
  {
    $className = $isNested ? 'NestedModule' : 'Module';

    return "<script>\nvar {$this->name} = new {$className}(\"{$this->name}\", \"{$this->uri}\");\n</script>";
  }

  /* ----------------------------------
    TABGROUP SUPPORT
  ---------------------------------- */
  /**
   * 
   */
  public function processNested(): void
  {
    $this->loadComponents();

    $this->index();
  }

  /**
   * Build Tab Module
   */
  public function buildNested(): string
  {
    ob_start();

    $this->processNested();

    $content = ob_get_contents();

    ob_clean();

    return $content;
  }
  /**
   * Write
   * Este método solo es invocado por TabGroupContent.View
   */
  public function write()
  {
    // La vista puede tener las siguientes nomenclaturas:
    //  a) module-directory/[module-name].view.php
    //  b) module-directory/View.php
    if (@file_exists($this->directory . DS . "{$this->name}.view.php"))
      include $this->directory . DS . "{$this->name}.view.php";
    elseif (@file_exists($this->directory . DS . "View.php"))
      include $this->directory . DS . "View.php";
    else
      trigger_error("La vista de {$this->name} no existe", E_USER_ERROR);
  }
}
