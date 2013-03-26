<?php
class main
{
	public function test()
	{
		return 'algo';
	}

	public function test_params($id = null)
	{
		if (is_null($id)) throw new RPC_INVALID_PARAMS;
		
		return $id;
	}

	public function getCookie()
	{
		if (empty($_COOKIE)) return array();
		
		return $_COOKIE;
	}

	

}
?>