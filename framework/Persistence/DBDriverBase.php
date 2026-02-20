<?php

namespace Atusan\Persistence;

abstract class DBDriverBase implements DBDriverInterface
{
  private $conn;
  private $affectedRows;

  function __construct(protected $host, protected $user, protected $pass, protected $db) {}
}
