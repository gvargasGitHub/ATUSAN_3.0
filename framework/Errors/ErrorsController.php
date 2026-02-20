<?php

namespace Atusan\Errors;

use Atusan\Log\Log;
use Atusan\Response\Response;
use Throwable;

class ErrorsController
{
  public static function handle_error($errno, $errstr, $errfile, $errline, $errcontext = NULL)
  {
    # if error reporting is off or add @ at begin of expression
    # doesn't show
    if (0 == error_reporting())
      return;

    # if exclude any type of error, doesn't show
    if (!(error_reporting() & $errno))
      return;

    # get debug backtrace
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

    # escapar
    $errstr = htmlspecialchars($errstr);

    switch ($errno) {
      case E_USER_NOTICE:
        $report = [
          'handler' => 'Notice',
          'message' => $errstr
        ];
        Response::instance()->notice($report['message']);
        break;
      case E_USER_WARNING:
        $report = [
          'handler' => 'Warning',
          'message' => $errstr
        ];
        Response::instance()->warning($report['message']);
        break;
      case E_USER_ERROR:
      default:
        $report = [
          'handler' => 'Error'
        ];
        Log::error("Error[$errno] $errstr in $errfile:$errline");

        if (APP_DEBUG) {
          $report['message'] = $errstr;
          $report['trace'] = $backtrace;
        } else {
          $report['message'] = 'Internal Server Error';
        }
        # limpia el BUFFER de salida
        while (ob_get_length() !== false)
          ob_end_clean();

        # establece los Encabezados de Documento
        http_response_code(500);

        exit(Response::instance()->error($report['message']));
    }

    return (true);
  }

  public static function handle_exceptions(Throwable $ex)
  {
    Log::error('Exception (' . get_class($ex) . '):' . $ex->getMessage()
      . ' in ' . $ex->getFile() . ':' . $ex->getLine());
    # limpia el BUFFER de salida
    while (ob_get_length() !== false)
      ob_end_clean();

    # establece los Encabezados de Documento
    http_response_code(500);

    exit(Response::instance()->error('Exception (' . get_class($ex) . '):' . $ex->getMessage()
      . ' in ' . $ex->getFile() . ':' . $ex->getLine()));
  }

  public static function handle_shutdown()
  {
    echo 'Script ejecutado con exito';
  }

  public static function register()
  {
    # Sujetador personalizado de errores (trigger_error)
    set_error_handler(['\Atusan\Errors\ErrorsController', 'handle_error']);

    # Excepciones
    set_exception_handler(['\Atusan\Errors\ErrorsController', 'handle_exceptions']);

    # Ejecución de cierre
    # register_shutdown_function(['\Atusan\Errors\ErrorsController', 'handle_shutdown']);
  }
}
