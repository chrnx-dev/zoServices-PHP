<?php

error_reporting(0);

define ( 'DS',  DIRECTORY_SEPARATOR);
/**
 * Server Main Path.
 **/
define ( 'SERVER_PATH',                dirname(__FILE__).DS);
define ( 'CLASS_PATH',                 SERVER_PATH.'classes'.DS);

define ( 'JSON_RPC_VERSION',  "2.0");

require_once CLASS_PATH.'zoServicesExceptions.inc.php';
require_once CLASS_PATH.'zoServicesConfig.class.php';
require_once CLASS_PATH.'zoServicesServer.class.php';

zoServiceConfigure::set('responses', SERVER_PATH. 'responses'.DS);
zoServiceConfigure::set('allows_cors', TRUE);


$server = new zoServicesServer;

$server->process();
?>