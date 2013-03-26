<?php
class zoServicesSender {

	private static $instance; 
	
	private $request = array();
	
	private $response = array();
	
	private $params = array ();

    private function __construct($Params= null)
    {
    	if (!empty($Params) && is_array($Params) )
    		foreach ($Params as $name => $value) $this->$name = $value;    
    }
    
    public static function getInstance($Params = null)
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class($Params);
        }
        return self::$instance;
    }
    
	private function generateId()
	{
		return md5( uniqid( microtime( true ), true ) );   
	}
	
	public function request($pMethod, $pParams) {
		$request = new stdClass;
		$request->jsonrpc = JSON_RPC_VERSION;
        $request->method = $pMethod;
        $request->params = $pParams;
        $request->id =$this->generateId();
		
		if ( zoServiceConfigure::get('is_batch') )
		{
			$this->request[] = $request;

		}
		else
		{
			$server = new zoServicesNet();
			//echo "sending request...<br/><pre>".print_r($request,true).'</pre><br />';
			$oResponse = json_decode($server->send($request));
			//echo "obtain response...<br/><pre>".print_r($oResponse,true).'</pre><br />';
			$server = null;
			return $oResponse;
			
			
		}
		
	
	}
	
	
	public function getRequest() 
	{
		return $this->request;
	}
	public function getResponses () 
	{
		$server = new zoServicesNet();
		//echo "sending request...<br/><pre>".print_r($this->request,true).'</pre><br />';
		$oResponse = json_decode($server->send($this->request));
		//echo "obtain response...<br/><pre>".print_r($oResponse,true).'</pre><br />';
		$server = null;
		return $oResponse;
	}
	
    public function __get($param)
    {
	
	}
	
	public function __set($param, $value) 
	{
	
		$this->params[$param]= $value;
	}
	
	
    public function __clone()
    {
        trigger_error('No se permite la clonaci�n.', E_USER_ERROR);
    }
    
    public function __wakeup()
    {
        trigger_error('No se permite deserializar.', E_USER_ERROR);
    }

}

?>