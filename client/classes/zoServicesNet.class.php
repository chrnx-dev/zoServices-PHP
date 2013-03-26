<?php
class zoServicesNet 
{
	private $config =  array
	( 
		 
		CURLOPT_HEADER => 0, 
		CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => '', 
		CURLOPT_RETURNTRANSFER => 1,

	);
	
	private $instance = null;
	
	public function __construct()
	{
		
	}
	
	private function isCuRL($sRequest)
	{
		$this->instance = curl_init();
		$cookie_str = '';

		foreach ($_COOKIE as $k => $v) 
        	$cookie_str .= urlencode($k) .'='. urlencode($v) .'; ';

		curl_setopt_array( $this->instance, $this->config );
		curl_setopt($this->instance, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->instance, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($this->instance, CURLOPT_URL,zoServiceConfigure::get('server'));
		curl_setopt($this->instance, CURLOPT_COOKIE, $cookie_str);
		
		curl_setopt($this->instance, CURLOPT_POSTFIELDS, $sRequest);
		$response_json = curl_exec( $this->instance);
		if ( curl_errno( $this->instance ) || ( curl_getinfo( $this->instance, CURLINFO_HTTP_CODE ) != 200 ) )
		{
			throw new RPC_INTERNAL_ERROR(curl_error($this->instance));
		}
		curl_close($this->instance);			
		return $response_json;
	}
	
	private function isSocket($sRequest)
	{
		//$sRequest = '';
		$crlf = "\r\n";
		$response_json = '';
		$request= '';
		$cookie_str = '';


		$request .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf; 
		$request .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf; 
		$request .= 'Accept-Language: en,en-us;q=0.7,es-mx;q=0.3' . $crlf;
		$request .= 'Accept-Encoding: gzip, deflate' . $crlf;
		   
		$request .= 'Content-Type: application/json; charset=UTF-8' . $crlf;


		foreach ($_COOKIE as $k => $v) 
        	$cookie_str .= urlencode($k) .'='. urlencode($v) .'; ';

        if (!empty($cookie_str)) 
        	$request .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf; 

        if (!empty($sRequest)) 
		{ 
			//$request .= 'Content-Type: application/x-www-form-urlencoded' . $crlf; 
			$request .= 'Content-Length: '. strlen($sRequest) . $crlf ; 
			$request .= 'Connection: Close' . $crlf.$crlf;
		}

		    
		$opciones = array
		(
			'http'=>array
			(
				'method'	=> "POST",
				'header'	=> $request,
				'content' 	=> $sRequest
			)
		);

		
		$ctx = stream_context_create($opciones); 
   		$fp = fopen(zoServiceConfigure::get('server'), 'rb', false, $ctx);

   		if (!$fp) throw new RPC_INTERNAL_ERROR;

   		$response_json = @stream_get_contents($fp); 

   		if ($response_json === false) throw new RPC_INTERNAL_ERROR;
		return $response_json;
		
		
	}
	
	public function send($oRequest) 
	{
		$sObject = json_encode($oRequest);
		/**
		 * Server call type.
		 */
		$serverType = zoServiceConfigure::get('server_type');
		switch ($serverType)
		{
			case CURL_ENGINE:
				return $this->isCuRL($sObject);
				break;
			case SOCKET_ENGINE:
				return $this->isSocket($sObject);
				break;
		}
		
	}
}


	
?>