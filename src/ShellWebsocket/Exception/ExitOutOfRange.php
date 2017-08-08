<?php

namespace Wapi\Daemon\ShellWebsocket\Exception;

class ExitOutOfRange extends ShellException {
  const CODE = 255;
  const MESSAGE = 'Exit status out of range';
}