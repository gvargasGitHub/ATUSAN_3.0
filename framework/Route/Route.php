<?php

namespace Atusan\Route;

use Atusan\Exceptions\NoticeException;
use Atusan\Exceptions\WarningException;

class Route
{
  use RouteBase;
  /**
   * Implementation
   */
  static public function implement(): void
  {
    include APP_DIRECTORY . DS . 'Route.php';
  }

  /**
   * Resolve
   */
  static public function resolve(): array
  {
    if (($routeType = self::findRouteByUri($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'])) === false)
      trigger_error(
        "La ruta {$_SERVER['REQUEST_URI']} no ha sido implementada para {$_SERVER['REQUEST_METHOD']}.",
        E_USER_ERROR
      );

    $controller = new $routeType->controller($app = new \App\Application(APP_NAME, APP_DIRECTORY), $routeType->uri);

    return [$app, $controller, $routeType];
  }

  /**
   * Redirect
   */
  static public function redirect($app, $uri)
  {
    if (($routeType = self::findRouteByUri('GET', $uri)) === false)
      trigger_error("La ruta {$uri} no ha sido implementada para GET.", E_USER_ERROR);

    return new $routeType->controller($app, $routeType->uri);
  }

  /**
   * Find Route By URI
   */
  static protected function findRouteByUri($method, $uri): mixed
  {
    foreach (self::$routes[$method] as $route)
      if ($route->uri == $uri) return $route;

    return false;
  }

  /**
   * MiddleWare
   */
  static public function middleware(string $filter, callable $addRoutes, string $redirect = '/')
  {
    self::$middlewareState = 1;
    self::$middlewareFilter = $filter;
    self::$middlewareRedirectUri = $redirect;

    if (!is_callable($addRoutes)) trigger_error('El segundo parámetro debe ser una función', E_USER_ERROR);

    call_user_func($addRoutes);
  }

  /**
   * Process
   */
  static public function process($controller, $type)
  {
    ob_start();
    try {
      // Si el "controller" es una instancia de ModuleInterface, entonces,
      // invocará la carga de los Componentes de Module
      if (is_a($controller, 'Atusan\\Module\\ModuleInterface'))
        $controller->loadComponents();

      // Valida que exista el método establecido en Route para resolver la petición
      if (!method_exists($controller, $type->resolve))
        trigger_error("El método {$type->resolve} no existe para {$controller->name}", E_USER_ERROR);

      // Resuelve la petición
      $controller->{$type->resolve}();
    } catch (NoticeException $notice) {
      trigger_error($notice->getMessage(), E_USER_NOTICE);
    } catch (WarningException $warning) {
      trigger_error($warning->getMessage(), E_USER_WARNING);
    } catch (\Exception $general) {
      trigger_error($general->getMessage(), E_USER_WARNING);
    }
  }
}
