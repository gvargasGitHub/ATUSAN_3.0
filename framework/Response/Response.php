<?php

namespace Atusan\Response;

use Atusan\Json\JsonUtil;
use Atusan\Module\ModuleInterface;
use Atusan\Template\Template;

class Response implements ResponseInterface
{
  /**
   * @var Array $data
   */
  private $data = [];

  /**
   * 
   */
  static private $instance;

  /**
   * Response
   * Recibe y establece la propiedad "module". Este valor
   * puede ser nulo para el caso de los controladores de
   * tipo "Service".
   */
  function __construct(protected ?ModuleInterface $module)
  {
    $this->data = [];
  }

  /**
   * View
   * Este método es invocado desde el método "index" de cada Módulo
   * extendido de la clase Atusan\Module.
   * Finaliza el tratamiento de la petición invocando Template::render
   * el cual, recibe como parámetro el módulo presente para incluir y
   * procesar la plantilla (template) establecida.
   */
  public function view(): void
  {
    exit(Template::render($this->module));
  }

  /**
   * Nested
   * Este método es invocado desde el método "index" de cada Módulo
   * extendido de la clase Atusan\NestedModule.
   * Realiza el tratamiento de la petición invocando Template::render
   * pero no finaliza el hilo.
   */
  public function nested(): void
  {
    Template::render($this->module);
  }

  /**
   * Add
   */
  public function add(string $key, mixed $value): void
  {
    $this->data[$key] = $value;
  }

  /**
   * Json
   */
  public function json(array $data = []): string
  {
    $this->data = array_merge($this->data, $data);
    exit(JsonUtil::toStringFormat(['status' => 'ok', 'data' => $this->data]));
  }

  /**
   * Error
   */
  public function error(string $message): void
  {
    echo match (CONTENT_TYPE_REQUESTED) {
      'HTML' => Template::renderError($message),
      'XHR' => JsonUtil::toStringFormat(['status' => 'error', 'message' => $message])
    };
  }

  /**
   * 
   */
  public function notice(string $message): void
  {
    echo match (CONTENT_TYPE_REQUESTED) {
      'HTML' => Template::renderNotice($message),
      'XHR' => exit(JsonUtil::toStringFormat(['status' => 'notice', 'message' => $message]))
    };
  }
  /**
   * 
   */
  public function warning(string $message): void
  {
    echo match (CONTENT_TYPE_REQUESTED) {
      'HTML' => Template::renderWarning($message),
      'XHR' => exit(JsonUtil::toStringFormat(['status' => 'warning', 'message' => $message]))
    };
  }

  public static function instance()
  {
    if (self::$instance == NULL)
      self::$instance = new self(NULL);

    return self::$instance;
  }
}
