<?php

namespace App\Classes;

use App\Models\AppModuleParentModel;
use Atusan\Module\Module;
use Atusan\Session\AppSession;

abstract class AppModuleParent extends Module
{
  function index()
  {
    $this->topbar->setTitle($this->app->title);
    $this->topbar->editItem('mi_account', 'text', AppSession::get('nombreUsuario'));
    $this->topbar->editItem('mi_version', 'text', 'Versión: ' . AppSession::get('version'));
  }

  function close()
  {
    AppSession::close();

    $this->response->json();
  }

  function keepAlive()
  {
    AppSession::keepAlive();

    $model = new AppModuleParentModel();

    $this->response->json($model->callLogout());
  }
}
