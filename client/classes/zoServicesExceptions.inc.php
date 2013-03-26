<?php
class RPC_JSON_PARSER extends Exception
{
    protected $message = 'JSON Parser Error in RPC Request';
    protected $code = -32700;
}

class RPC_INVALID_REQUEST extends Exception
{
    protected $message = 'Malformed or Invalid Request';
    protected $code = -32600;
}

class RPC_METHOD_NOT_EXISTS extends Exception
{
    protected $code = -32601;

    public function __construct($method, $code = 0, Exception $previous = null) 
    {
        $message = "Method $method doesn't exists";
        parent::__construct($message, $code, $previous);
    }
}

class RPC_INVALID_PARAMS extends Exception
{
    protected $message = 'Invalid Params';
    protected $code = -32602;
}

class RPC_INTERNAL_ERROR extends Exception
{
    protected $message = 'Server Internal Error';
    protected $code = -32603;
}


class RPC_CLASS_NOT_FOUND extends Exception
{
    protected $code = -32604;

    public function __construct($class, $code = 0, Exception $previous = null) 
    {
        $message = "Class $class doesn't exists";
        parent::__construct($message, $code, $previous);
    }
}

class RPC_INVALID_RESPONSE extends Exception
{
    protected $message = 'Malformed or Invalid Response';
    protected $code = -32605;
}

?>