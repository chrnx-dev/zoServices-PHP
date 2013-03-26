zoServices
=====================
This project helps the creation of web services using JSON RPC 2.0 specification, across multiple programming 
languages. The main point of this is to help developers build Web services in a simple way and focus only on the logistics and programming functionality. zoServices provide the server/client infrastructure to comunicate their services via internet.

This package Contains the Server and Client's PHP Implementation of zoServices.

##Changelog
- - -

zoServices 1.0

- Support GET and POST Request.
- Adding Custom Exceptions.
- Adding Config Class.
- Adding CORS config.
- Refactoring code.

## Server

1. Install
- - -
To Use Server just copy all content of Server folder to your htdocs folder, where server will manage all requests
if you want to separate CLASS folder and RESPONSE folder see Advance Configuration.

2. Configuration
- - -
You can define whom class will use as a default class using for request that don't need specify class name, for
default this is configurate to "Main Class" in response folder, but you can modify the static method "defaultClass"
in zoServicesConfig.class.php in "classes" folder.

```php
public static function defaultClass(){
	return 'main';
}
```

3. Advance Configuration.
- - -
If you separate CLASS folder you will need configure in main page the next configure.
define ( 'CLASS_PATH',                 'PATH to Server Classes');

If you separate RESPONSE folder you will need configure in main page the next configure.
zoServiceConfigure::set('responses',  'responses_path');

4. TEST Server
- - -
For test if you server is configurate correctly, open your favorite browser and input the url where you install
the server and will replay this JSON string.

{"id":null,"error":{"code":-32700,"message":"JSON Parser Error in RPC Request"},"jsonrpc":"2.0"}


## Client

1. Install
- - -
To Use Client just copy all content of Client folder to your project folder, where server will manage all requests
if you want to separate CLASS folder and RESPONSE folder see next topic.

2. Configuration
- - -
If you separate CLASS folder you will need configure in zoServicesClient.inc.php the next configure.
define ( 'CLASS_PATH',                 'PATH to Server Classes');

to configurate wich is the server modify.
zoServiceConfigure::set('server',   'path to server');

To Select the way that client comunnicate with server via Curl (CURL_ENGINE) or Socket (SOCKET_ENGINE).
zoServiceConfigure::set('server_type', 	CURL_ENGINE);



3. TEST Client
- - -
For test configure you client with a previously installed server, include in your project, then when you can use
the client to call remote classes and methods

require_once zoServicesClient.inc.php

and Instance Client Object.
$client = new zoServicesClient();

Calling Method from default class.
$client->test();

Calling Method from Class.
$client->class->method();

Batch
$client->startBatch();
$client->method();
$client->class->method();

//get results
$client->endBatch();





##Support
- - -
If Found an Issue, doubt or complain related to this project please contact to

Diego Resendez <diego.resendez@zero-oneit.com>
Developer and Lead of the Project.


##Donations
- - -
If you want to contribute with this project you can donate via pay pal to
Diego Resendez <diego.resendez@zero-oneit.com>

If you want to contribute helping to developt this project.
send mail to <contributors@zero-oneit.com>
