<?php

namespace Atusan\Module;

abstract class NestedModule extends ModuleBase
{
  /**
   * Nested
   * Este método es invicado por TabGroupContent.View.
   */
  public function nested()
  {
    echo $this->buildNested();
  }

  /**
   * Nested to Json
   * Este método es invocado por Route::process para las rutas establecidas
   * mediante Route::nested(Uri, Controller)
   */
  public function nestedToJson()
  {
    $this->response->add('name', $this->name);
    $this->response->add('title', $this->title);
    $this->response->add('content', $this->buildNested());
    $this->response->json();
  }

  public function jsDeclareNested(): string
  {
    return $this->jsDeclare(true);
  }
}
