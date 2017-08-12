<?php
namespace Wapi\Daemon\ShellWebsocket;

use React\ChildProcess\Process;
use React\Promise\Deferred;
use React\Promise\FulfilledPromise;
use Wapi\Daemon\ShellWebsocket\Exception\ShellException;
use Wapi\Exception\AccessDenied;
use Wapi\Exception\ClockMismatch;
use Wapi\Exception\WapiException;
use Wapi\Message;
use Wapi\MessageHandler\MessageHandlerBase;
use Wapi\ServiceManager;

class MessageHandler extends MessageHandlerBase {
  
  static function getMethods() {
    return [
      'ping' => 'ping',
      'die' => 'stop',
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
    $secret = ServiceManager::app()->server_secret;
  
    if(!$this->message->verifyTimestamp()) {
      throw new ClockMismatch();
    }
  
    if(!$this->message->verifyCheck($secret)) {
      throw new AccessDenied();
    }
  }
  
  public function ping() {
    return new FulfilledPromise(microtime(TRUE));
  }
  
  public function stop() {
    exit;
  }
  
  public function exec($command) {
    echo "$command\n";
    $output = '';
    $error = '';
    $deferred = new Deferred();
    
    $loop = ServiceManager::loop();
    $process = new Process($command);
    $process->start($loop);
  
    $process->stdout->on('data', function ($chunk) use(&$output) {
      $output .= $chunk;
    });
  
  
    $process->stdout->on('error', function (\Exception $e) use (&$error) {
      $error .= $e->getMessage();
    });
    
    $process->stderr->on('data', function ($chunk) use(&$error)  {
      $error .= $chunk;
    });
  
    $process->on('exit', function($exitCode) use (&$output, &$error, $deferred) {
      if($exitCode) {
        $deferred->reject(ShellException::exitCode($exitCode, $error ?: $output));
      } else {
        $deferred->resolve(trim($output));
      }
    });
    
    return $deferred->promise();
  }
  
}