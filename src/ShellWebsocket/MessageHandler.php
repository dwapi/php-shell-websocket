<?php
namespace Wapi\Daemon\ShellWebsocket;

use React\ChildProcess\Process;
use React\Promise\Deferred;
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
    $output = '';
    $error = '';
    $deferred = new Deferred();
    
    $loop = ServiceManager::service('loop');
    $process = new Process($command);
    $process->start($loop);
  
    $process->stdout->on('data', function ($chunk) use(&$output) {
      $output .= $chunk;
    });
    
    $process->stderr->on('data', function ($chunk) use(&$error)  {
      $error .= $chunk;
    });
  
    $process->on('exit', function($exitCode) use ($output, $error, $deferred) {
      if($exitCode) {
        $deferred->reject($error);
      } else {
        $deferred->resolve($output);
      }
    });
    
    return $deferred->promise();
  }
  
}