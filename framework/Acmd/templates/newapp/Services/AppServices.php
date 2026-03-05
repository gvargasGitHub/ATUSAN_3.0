<?php

namespace App\Services;

use App\Models\AppModel;
use Atusan\Services\ServiceBase;
use Atusan\Session\AppSession;

class AppServices extends ServiceBase
{
  function close()
  {
    AppSession::close();

    $this->response->json();
  }

  function keepAlive()
  {
    AppSession::keepAlive();

    $model = new AppModel();

    $this->response->json($model->callLogout());
  }

  function checkLogoutState()
  {
    $model = new AppModel();

    $this->response->json($model->callLogout());
  }
}
