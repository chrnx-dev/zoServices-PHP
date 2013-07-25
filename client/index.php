<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

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


zoServiceConfigure::set('server',   'http://localhost/server/');
zoServiceConfigure::set('server_type', 	CURL_ENGINE);

$client = new zoServicesClient();
$client->startBatch();
echo '<pre>'.print_r($client->test(),true).'</pre>';
echo '<br />';
echo '<pre>'.print_r($client->services->getServiceInfo(),true).'</pre>';
echo '<pre>'.print_r($client->endBatch(),true).'</pre>';


