<?php
namespace ShellWebsocket;

use Clue\React\Shell\ProcessLauncher;
use Wapi\Exception\AccessDenied;
use Wapi\Exception\ClockMismatch;
use Wapi\Message;
use Wapi\MessageHandler\MessageHandlerBase;
use Wapi\ServiceManager;

class MessageHandler extends MessageHandlerBase {
  
  static function getMethods() {
    return [
      'exec' => 'exec',
    ];
  }
  
  static function isApplicable(Message $message) {
    if(!($message->client->getPath() == '/shell-access')) {
      return FALSE;
    }
    return TRUE;
  }
  
  public function access() {
    $secret = ServiceManager::service('app')->server_secret;
  
    if(!$this->message->verifyTimestamp()) {
      throw new ClockMismatch();
    }
  
    if(!$this->message->verifyCheck($secret)) {
      throw new AccessDenied();
    }
  }
  
  public function exec($command) {
    $launcher = new ProcessLauncher(ServiceManager::service('loop'));
    $shell = $launcher->createDeferredShell('bash');
    return $shell->execute($command)->always(function () use ($shell) {
      $shell->end();
    });
  }
  
}