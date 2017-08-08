<?php

namespace Wapi\Daemon\ShellWebsocket\Exception;

class CannotExecute extends ShellException {
  const CODE = 126;
  const MESSAGE = 'Command is not an executable';
}