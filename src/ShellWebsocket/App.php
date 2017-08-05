<?php
namespace ShellWebsocket;

class App extends \Wapi\App {
  
  public function getMessageHandlers() {
    return [
      '\ShellWebsocket\MessageHandler'
    ];
  }
  
}