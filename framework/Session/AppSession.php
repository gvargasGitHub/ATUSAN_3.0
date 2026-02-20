<?php

namespace Atusan\Session;

class AppSession
{
  /**
   * 
   */
  static public function start(): string
  {
    if (session_status() !== PHP_SESSION_ACTIVE)
      session_start();

    if (!array_key_exists(APP_NAME, $_SESSION)) $_SESSION[APP_NAME]
      = [
        'globals' => ['auth' => 0],
        'started' => time(),
        'idle_time_exceeded' => 0,
        'idle_time_allowed' => 0
      ];

    return session_id();
  }

  static public function keepAlive(): string
  {
    return self::start();
  }

  static public function writeClose()
  {
    session_write_close();
  }

  /**
   * 
   */
  static public function destroy()
  {
    if (!self::validId(session_id())) {
      session_name(self::NAME);
      session_start();
    }

    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();

      # define la cookie para ser enviada junto con el resto de los
      # headers de HTTP. 
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }

    session_destroy();
  }

  /**
   * 
   */
  static public function close()
  {
    if (!self::validId(session_id())) trigger_error('La sesión no existe.');

    $_SESSION[APP_NAME]['globals'] = ['auth' => 0];
  }

  /**
   * 
   */
  static public function validate(): bool
  {
    if ($_SESSION[APP_NAME]['idle_time_allowed'] == 0) return true;

    $idle = (int) time() - (int) $_SESSION[APP_NAME]['started'];

    return ($idle > $_SESSION[APP_NAME]['idle_time_allowed']);
  }

  /**
   * 
   */
  static protected function validId($id): bool
  {
    return preg_match('/^[-,a-zA-z0-9]{1,128}$/', $id) > 0;
  }

  /**
   * Get Global
   */
  static public function get(string $key): mixed
  {
    return ($_SESSION[APP_NAME]['globals'][$key]) ?: null;
  }

  /**
   * Set Global
   */
  static public function set(string $key, mixed $value): void
  {
    $_SESSION[APP_NAME]['globals'][$key] = $value;
  }

  /**
   * Set or Get Auth State
   */
  static public function auth(?bool $state): bool
  {
    if (!is_null($state)) $_SESSION[APP_NAME]['globals']['auth'] = $state;

    return $_SESSION[APP_NAME]['globals']['auth'];
  }
}
