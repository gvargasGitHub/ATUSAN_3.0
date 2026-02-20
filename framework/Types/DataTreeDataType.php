<?php

namespace Atusan\Types;

class DataTreeDataType
{
  public $children = [];

  function __construct(public $index, public $data) {}

  function hasChildren(): bool
  {
    return (count($this->children) > 0);
  }
}
