<?php

if (getenv('COMPOSER_VENDOR_DIR') && is_file(__DIR__ . '/../' . getenv('COMPOSER_VENDOR_DIR') . '/autoload.php')) {
  require(__DIR__ . '/../' . getenv('COMPOSER_VENDOR_DIR') . '/autoload.php');
} elseif (is_file(__DIR__ . '/../vendor/autoload.php')) {
  require(__DIR__ . '/../vendor/autoload.php');
} elseif (is_file(__DIR__ . '/../../../autoload.php')) {
  require(__DIR__ . '/../../../autoload.php');
}

$params = \Wapi\Daemon::parseArgs($argv);
$params['host'] = '127.0.0.1';
$params['port'] = 33556;

$daemon = new \Wapi\Daemon($params, '\Wapi\Daemon\ShellWebsocket\App');

$daemon->run();