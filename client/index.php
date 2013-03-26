<?php
define ( 'DS',  DIRECTORY_SEPARATOR);
/**
 * Server Main Path.
 **/
define ( 'CLIENT_PATH',                dirname(__FILE__).DS);
define ( 'CLASS_PATH',                 CLIENT_PATH.'classes'.DS);

define ( 'JSON_RPC_VERSION',  "2.0");
define ( 'CURL_ENGINE',			  0);
define ( 'SOCKET_ENGINE',		  1);


require_once CLASS_PATH.'zoServicesExceptions.inc.php';
require_once CLASS_PATH.'zoServicesConfig.class.php';
require_once CLASS_PATH.'zoServicesSender.class.php';
require_once CLASS_PATH.'zoServicesNet.class.php';
require_once CLASS_PATH.'zoServicesClient.class.php';


zoServiceConfigure::set('server',   'path to server');
zoServiceConfigure::set('server_type', 	CURL_ENGINE);
