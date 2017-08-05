<?php
namespace Wapi\Daemon\ShellWebsocket;

class App extends \Wapi\App {
  
  public function getMessageHandlers() {
    return [
      '\Wapi\Daemon\ShellWebsocket\MessageHandler'
    ];
  }
  
}