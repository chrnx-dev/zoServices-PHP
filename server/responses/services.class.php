<?php
class Services
{
	public function getServiceInfo()
	{
		return array
		(
			'version'			=> zoServiceConfigure::get('version'),
			'developer'			=> zoServiceConfigure::get('developer'),
			'license'			=> zoServiceConfigure::get('license')
			
		);
	}



	


}
?>