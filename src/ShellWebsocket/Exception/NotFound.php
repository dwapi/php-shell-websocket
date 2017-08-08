<?php

namespace Wapi\Daemon\ShellWebsocket\Exception;

class NotFound extends ShellException {
  const CODE = 127;
  const MESSAGE = 'Command not found';
}