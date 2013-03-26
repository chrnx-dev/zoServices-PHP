<?php
class zoServicesServer 
{
	private $request = null;
	private $response = null;

	public function __construct()
	{
		register_shutdown_function(array($this, "returnResponse"));
		header('Cache-Control: no-cache, must-revalidate');
    	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    	header('Content-type: application/json');

    	if (zoServiceConfigure::get('allow_cors'))
    	{
    		header('Access-Control-Allow-Origin: *');
			header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
			$cors_headers = zoServiceConfigure::get('allow_cors_headers');
			if (!empty($cors_headers))
				header('Access-Control-Allow-Headers: Content-Type,'.implode(',',$cors_headers));
			else
				header('Access-Control-Allow-Headers: Content-Type');
    	}
	}

	public function process($data = null)
	{
		try
		{
			$request_json = '';
			if (!empty($data) && is_array($data))
			{
				$this->request = (object) $data;
			}
			elseif (!empty($_GET))
			{
				if ( isset ($_GET['request'] ) ) 
				{
	                $request_json = $_GET['request'];
	                $request_json = str_replace('\\', '', $request_json);    
	            }
	            else 
	            {
	                $request = new stdClass();
	                isset($_GET['jsonrpc'])? $request->jsonrpc = $_GET['jsonrpc']:null;
	                isset($_GET['method'])? $request->method = $_GET['method']:null;
	                isset($_GET['id'])?$request->id = $_GET['id']:null;
	                isset($_GET['params'])? $request->params = json_decode($_GET['params'],true):null;

	                if ( ! $request_json = json_encode($request) ){
	                    $request_json = '';
	                }

	            }
			}
			else
			{
				ob_start();
	        	$request_json = file_get_contents( 'php://input' );
				$request_json = str_replace('\\"', '"', $request_json);
		        ob_clean();
			}

			if (!$this->request = zoServiceConfigure::__parseJson($request_json))
			{
	            throw new RPC_JSON_PARSER;
	        }

	        $this->getResponse();
	    }
	    catch (RPC_JSON_PARSER $e)
	    {
	    	$this->response =$this->getError($e->getCode(),$e->getMessage());
	    }
	    catch (Exception $e)
	    {
	    	$ex = new RPC_INTERNAL_ERROR;
	    	$this->response= $this->getError($ex->getCode(),$ex->getMessage());
	    }    
	}

	public function returnResponse()
	{
		$error = error_get_last();
		if (!empty($error) && in_array($error['type'], array(E_USER_ERROR, E_ERROR) ) )
		{
			$e = new RPC_INTERNAL_ERROR;
			echo json_encode($this->getError($e->getCode(),$e->getMessage()));
		}
		else
		{
			echo json_encode($this->response);
		}	

	}

	private function getResponse()
	{
		if ($this->isBatch())
		{
			$this->response = $this->batch();
		}
		else
		{
			$this->response= $this->single();
		}
	}

	private function batch()
	{
		$oResult = array();
		$requests = $this->request;
		foreach ($requests as $request) 
		{
			$this->request = $request;
			$oResult[] =  $this->_response($request);
		}

		return $oResult;
	}

	private function single()
	{
		return $this->_response($this->request);
	}

	
	private function _response($request)
	{
		try
		{
			if ( ! zoServiceConfigure::__checkRequest($request) )
			{
	            throw new RPC_INVALID_REQUEST;
	            
	        }
	        return $this->execMethod($request);
		}
		catch (RPC_INVALID_REQUEST $e)
		{
			return $this->getError($e->getCode(),$e->getMessage());
		}

		catch (RPC_CLASS_NOT_FOUND $e)
		{
			return $this->getError($e->getCode(),$e->getMessage(), $this->request->id);
		}

		catch (RPC_METHOD_NOT_EXISTS $e)
		{
			return $this->getError($e->getCode(),$e->getMessage(), $this->request->id);
		}

		catch (RPC_INVALID_PARAMS $e)
		{
			return $this->getError($e->getCode(),$e->getMessage(), $this->request->id);
		}
		
	}

	/**
     * 
     * Call a Response Method from a Class
     */
    private function execMethod($oRequest)
    {
       
        $methodRaw = $oRequest->method;
        $methodRaw = explode('.', $methodRaw);
         
        if ( count( $methodRaw) == 1 )
        {
            $class = zoServiceConfigure::defaultClass();
            $method= $methodRaw[0];
        }
        else
        {
            list($class, $method) = $methodRaw;
           
        }
		
		if ( !file_exists( zoServiceConfigure::get('responses')."{$class}.class.php") ) 
		{
			throw new RPC_CLASS_NOT_FOUND($class);	
		}
		try 
		{
		 	require_once zoServiceConfigure::get('responses')."{$class}.class.php";    
		 
	        $ServerClass          	= new ReflectionClass($class);
	        $service              	= $ServerClass->newInstanceArgs();
	        $methodObject         	= $ServerClass->getMethod($method);
	        $methodParameters     	= $methodObject->getParameters();

	        $oResponse = new stdClass();
	        $oResponse->result 	= $methodObject->invokeArgs($service, $oRequest->params);
	           
	        
	        $oResponse->jsonrpc 	= JSON_RPC_VERSION;
	        $oResponse->id        	= $oRequest->id;
	        
	        if (!is_null($oResponse->result)) return $oResponse;
		} 
		catch (ReflectionException $e) 
		{
		 	throw new RPC_METHOD_NOT_EXISTS($method);
		} 
        
    }

	private function getError($code, $message, $id = null)
	{
		$exception = new stdClass;
	    $exception->id = $id;
	    $exception->error = new stdClass;
	    $exception->error->code = $code;
	    $exception->error->message =$message;
	    $exception->jsonrpc = JSON_RPC_VERSION;
	    return $exception;
	}

	private function isBatch()
	{
		return is_array($this->request);
	}
	
}
?>