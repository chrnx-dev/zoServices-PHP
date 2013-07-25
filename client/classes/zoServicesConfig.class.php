<?php
class zoServiceConfigure
{
	private static $settings = array
	(
		'allow_cors'				=> false,
		'allow_cors_headers'		=> '',
		'test'						=> false,
		'version'					=> '1.0 PHP Client Code Name -:Chronos:-',
		'developer'					=> 'Diego Resendez <diego.resendez@zero-oneit.com>',
		'license'					=> 'Apache License v2.0',
		'is_batch'					=> false

	);

	public static function _isset($setting)
	{
		return isset(self::$settings[$setting]);
	}

	public static function get($setting)
	{
		if (self::_isset($setting)) return self::$settings[$setting];

		throw new RPC_INTERNAL_ERROR;
		
	}

	public static function set($setting, $value)
	{
		self::$settings[$setting] = $value;
	}

	public static function _test()
	{
		echo '<br/><h1>zoServiceConfigure</h1><pre>'.print_r(self::$settings, true).'</pre><br/>';
	}

	public static function defaultClass()
	{
		return 'main';
	}

	/**
    *
    * Check and return if json string is correct.
    * @param json $pData
    */
    public static function __parseJson( $pData ){
        $data = json_decode( $pData, false );
        return $data;
    }

    public static function __checkRequest($oData){
        
        if ( !is_object( $oData ) || !isset( $oData->jsonrpc ) || $oData->jsonrpc !== JSON_RPC_VERSION || !isset(
        $oData->method ) || !is_string( $oData->method ) || !$oData->method || ( isset(
        $oData->params ) && !is_array( $oData->params ) ) || !isset( $oData->id ) )
        {
    
            return false;
        }
    
    
        if ( is_null( $oData->params ) )
        {
            $oData->params = array();
        }
    
        return true;
    }

     private function __checkResponse( $pObject, $pRequest )
    {
        if ( !is_object( $pObject ) || !isset( $pObject->jsonrpc ) || $pObject->jsonrpc !== JSON_RPC_VERSION || ( !isset(
        $pObject->result ) && !isset( $pObject->error ) ) || ( isset( $pObject->result ) && ( !isset(
        $pObject->id ) || $pObject->id !== $pRequest->id ) ) || ( isset( $pObject->error ) && ( !is_object(
        $pObject->error ) || !isset( $pObject->error->code ) || !isset( $pObject->error->message ) ) ) )
        {
            return false;
        }
        if ( isset( $pObject->error ) )
        {
           throw new Exception( $pObject->error->message, $pObject->error->code );
        }
		
		return true;
    }
}
?>
