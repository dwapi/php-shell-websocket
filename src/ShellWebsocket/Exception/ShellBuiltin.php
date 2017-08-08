<?php

namespace Wapi\Daemon\ShellWebsocket\Exception;

class ShellBuiltin extends ShellException {
  const CODE = 2;
  const MESSAGE = 'Missing keyword or command, or permission problem';
}