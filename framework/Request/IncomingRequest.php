<?php

namespace Atusan\Request;

class IncomingRequest
{
  static function resolve(): OutgoingRequest
  {
    $s = 'X-Requested-With';
    $v = 'XMLHttpRequest';
    $h = apache_request_headers();

    define(
      'CONTENT_TYPE_REQUESTED',
      (array_key_exists($s, $h) && $h[$s] = $v) ? 'XHR' : 'HTML'
    );

    return new OutgoingRequest();
  }
}
