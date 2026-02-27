<?php

namespace Atusan\Controls;

abstract class DataViewControlBase
{
  /**
   * @var
   * String $name
   */
  /**
   * @var
   * XmlExtended $xml
   */
  /**
   * @var
   * DataViewBase $view
   */

  /**
   * @var
   * String $type
   */
  protected $type;

  protected $data;

  protected $row;

  function __construct(protected $name, protected $xml, protected $view)
  {
    $this->type = preg_match('/^Control$/', $this->xml->getName())
      ? $this->xml->getAttribute('type')
      : substr($this->xml->getName(), 7);
  }

  abstract function getId(): string;

  public function getName(): string
  {
    return $this->name;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function setType(string $type): void
  {
    $this->type = $type;
  }

  public function getData(): array
  {
    return $this->data;
  }

  public function setData(array $dt): void
  {
    $this->data = $dt;
  }

  public function getValue(): mixed
  {
    return $this->data[$this->name] ?? null;
  }

  public function getRow(): int
  {
    return $this->row;
  }

  public function setRow($r): void
  {
    $this->row = $r + 1;
  }

  public function setEnable(bool $enable)
  {
    $this->xml->setAttribute('html:disabled', 'html', $enable ? '' : 'disabled');
  }

  abstract public function write();
}
