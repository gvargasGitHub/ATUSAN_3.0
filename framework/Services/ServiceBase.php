<?php

namespace Atusan\Services;

use Atusan\Response\Response;

abstract class ServiceBase
{
  function __construct()
  {
    $this->response = new Response(null);
  }
}
