<?php

namespace Wapi\Daemon\ShellWebsocket\Exception;

class InvalidtArgumentToExit extends ShellException {
  const CODE = 128;
  const MESSAGE = 'Invalid argument to exit';
}