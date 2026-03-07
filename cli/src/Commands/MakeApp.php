<?php

namespace AtusanCLI\Commands;

class MakeApp extends MakeBase
{
  function required(): array
  {
    return ['--name'];
  }

  function defaults(): array
  {
    return [
      '--title' => $this->args['--name'],
      '--start' => 'modAcceso'
    ];
  }

  function resolver()
  {
    // Valida si ya existe la carpeta de la Aplicación
    if ($this->checkIfAppExists($this->args['--name']))
      throw new \Exception("{$this->args['--name']} ya existe.");

    $appDir = APP_ROOT . DS . 'application' . DS . $this->args['--name'];

    // Copia plantilla de la Aplicación
    self::copyR(
      ATUSANCLI_SRC . DS . 'Templates' . DS . 'newapp',
      APP_ROOT . DS . 'application' . DS . $this->args['--name']
    );

    // Edita contenidos
    $xml = file_get_contents($appDir . DS . 'Manifest.xml');
    $xml = str_replace('app_name', $this->args['--name'], $xml);
    $xml = str_replace('app_title', $this->args['--title'], $xml);
    $xml = str_replace('app_start', $this->args['--start'], $xml);
    file_put_contents($appDir . DS . 'Manifest.xml', $xml);

    // Copia recursos CSS y JS
    self::copyR(
      ATUSANCLI_SRC . DS . 'Templates' . DS . 'statics' . DS . 'css',
      APP_ROOT . DS . 'public' . DS . $this->args['--name'] . DS . 'css'
    );
    self::copyR(
      ATUSANCLI_SRC . DS . 'Templates' . DS . 'statics' . DS . 'js',
      APP_ROOT . DS . 'public' . DS . $this->args['--name'] . DS . 'js'
    );

    // Copia archivo .env
    self::copy(
      ATUSANCLI_SRC . DS . 'Templates' . DS . 'statics' . DS . 'env' . DS . '.env',
      APP_ROOT . DS . '.env-' . $this->args['--name']
    );
    $env = file_get_contents(APP_ROOT . DS . '.env-' . $this->args['--name']);
    $env = str_replace('app_name', $this->args['--name'], $env);
    file_put_contents(APP_ROOT . DS . '.env-' . $this->args['--name'], $env);

    echo "La aplicación {$this->args['--name']} se creó exitosamente." . EOL;
  }
}
