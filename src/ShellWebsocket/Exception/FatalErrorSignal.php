<?php

namespace Wapi\Daemon\ShellWebsocket\Exception;

class FatalErrorSignal extends ShellException {
  const MESSAGE = 'Fatal error';
  
  public function __construct($signal, $error = NULL) {
    $code = 128 + $signal;
    $message = $error ?: static::MESSAGE . " signal $signal";
    parent::__construct($message , $code, $signal);
  }
  
}