<?php

namespace Atusan\Response;

interface ResponseInterface
{
  public function add(string $key, mixed $value): void;

  public function view(): void;

  public function json(array $data = []): string;

  public function error(string $message): void;
}
