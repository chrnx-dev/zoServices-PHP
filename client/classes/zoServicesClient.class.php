<?php
class zoServicesClient
{
	
	private $classes = array();
	private $push = null;
	

	function __construct( $server = null)
	{
		if (!empty($server) )
		{
			zoServiceConfigure::set('server', $server); 
		}

		$this->push = zoServicesSender::getInstance();
	}

	public function __get( $pObject )
	{
		if ( ! isset($this->classes[$pObject] ) )
		$this->set( $pObject);
			
		return $this->classes[$pObject];
	}


	private function set( $pClass )
	{
		$isBatch = zoServiceConfigure::get('is_batch');

		eval (
			"class $pClass {
				
				
				function __call( \$pMethod, array \$pParams) {
					
					return zoServicesSender::getInstance()->request(__CLASS__.'.'.\$pMethod, \$pParams);
					
				}
			 };
			 
			 \$pObject = new $pClass();
			 "
		);

		$this->classes[$pClass] = $pObject;

	}
	
	public function startBatch() 
	{
		zoServiceConfigure::set('is_batch', true);
	}

	public function endBatch() 
	{
		zoServiceConfigure::set('is_batch', false);
		return $this->push->getResponses();
	}

	public function __call( $pMethod, array $pParams )
	{
		 
		return $this->push->request($pMethod, $pParams);
		
	}

}




?>