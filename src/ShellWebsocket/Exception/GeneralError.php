<?php

namespace Wapi\Daemon\ShellWebsocket\Exception;

class GeneralError extends ShellException {
  const CODE = 1;
  const MESSAGE = 'General error';
}