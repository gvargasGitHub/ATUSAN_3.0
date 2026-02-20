<?php

namespace Atusan\Request;

class OutgoingRequest implements RequestInterface
{
  /**
   * @var Array routeParams
   */
  private $routeParams;

  /**
   * 
   */
  private static $request;

  private array $get;
  private array $post;
  private array $server;
  private array $headers;
  private array $json;

  private function __construct()
  {
    $this->get     = $_GET;
    $this->post    = $_POST;
    $this->server  = $_SERVER;
    $this->headers = $this->parseHeaders();
    $this->json    = $this->parseJsonBody();
  }

  public static function capture(): self
  {
    $s = 'X-Requested-With';
    $v = 'XMLHttpRequest';
    $h = apache_request_headers();

    define(
      'CONTENT_TYPE_REQUESTED',
      (array_key_exists($s, $h) && $h[$s] = $v) ? 'XHR' : 'HTML'
    );

    if (self::$request == NULL)
      self::$request = new self();

    return self::$request;
  }

  public function method(): string
  {
    return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
  }

  public function uri(): string
  {
    return strtok($this->server['REQUEST_URI'] ?? '/', '?');
  }

  /**
   * 
   */
  function get(string $key, mixed $default = null): mixed
  {
    if (array_key_exists($key, $this->json)) {
      return $this->json[$key];
    }

    if (array_key_exists($key, $this->post)) {
      return $this->post[$key];
    }

    if (array_key_exists($key, $this->get)) {
      return $this->get[$key];
    }

    return $default;
  }

  public function all(): array
  {
    return array_merge($this->get, $this->post, $this->json);
  }

  public function print(): string
  {
    return print_r($this->bodyData, 1);
  }

  /**
   * 
   */
  function has(string $key): bool
  {
    return array_key_exists($key, $this->bodyData);
  }

  /**
   * Get Route Param
   */
  public function getRouteInput(string $key): mixed
  {
    return array_key_exists($key, $this->routeParams) ? $this->routeParams[$key] : null;
  }

  public function header(string $key): ?string
  {
    return $this->headers[strtolower($key)] ?? null;
  }

  public function isJson(): bool
  {
    return str_contains(
      strtolower($this->header('Content-Type') ?? ''),
      'application/json'
    );
  }

  public function json(): array
  {
    return $this->json;
  }

  private function parseJsonBody(): array
  {
    if ($this->method() === 'GET') {
      return [];
    }

    $contentType = strtolower($this->header('Content-Type') ?? '');

    if (!str_contains($contentType, 'application/json')) {
      return [];
    }

    $raw = file_get_contents('php://input');
    if (!$raw) {
      return [];
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
  }

  private function parseHeaders(): array
  {
    $headers = [];

    foreach ($this->server as $key => $value) {
      if (str_starts_with($key, 'HTTP_')) {
        $name = strtolower(str_replace('_', '-', substr($key, 5)));
        $headers[$name] = $value;
      }
    }

    return $headers;
  }

  public static function instance()
  {
    if (self::$request == NULL)
      self::$request = new self();

    return self::$request;
  }
}
