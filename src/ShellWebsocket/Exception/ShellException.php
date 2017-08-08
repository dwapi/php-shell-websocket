<?php

namespace Wapi\Daemon\ShellWebsocket\Exception;

use Wapi\Exception\WapiException;

class ShellException extends WapiException {
  const CODE = 1;
  const MESSAGE = 'General error';
  
  static function exitCode($exitCode, $error) {
    $error = $error ?: NULL;
    $exception = NULL;
    switch ($exitCode) {
      case 1:
        return new GeneralError($error);
      case 2:
        return new ShellBuiltin($error);
      case 126:
        return new CannotExecute($error);
      case 127:
        return new NotFound($error);
      case 255:
        return new ExitOutOfRange($error);
    }
    
    if($exitCode > 128 && $exitCode < 255) {
      return new FatalErrorSignal($exitCode - 128, $error);
    }
    
    return new ShellException($error ?: "Exit code $exitCode", $exitCode);
  }
}