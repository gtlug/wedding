<?php
require_once("Abstract.php");
/**
 * The Home_IndexController
 * The name and location of this class is important.
 * "Home" is the module name
 * "Index" is the controller name
 * These two make up the first two parts of a three part
 * URI scheme.  The third part is the action name, which are
 * methods in the conroller class.
 */
class Photos_IndexController extends Photos_Controller_Abstract
{
	public function init()
	{
		parent::init();
	}
	
	/**
	* This action can be accessed at
	* /home
	* /home/index
	* /home/index/index
	*   M     C     A
	*/
	public function indexAction()
	{
		$NiX0n = "74154383@N00";
		
		//phpinfo();die();
				
		header("content-type: text/plain");
		$results = $this->_flickr->tagSearch(
			'whitevanberlowedding', 
			array(
				'user_id' => $NiX0n,
				//'privacy_filter' => 5
			)
		);
		
		foreach($results as $result)
		{
			print_r($result);
		}
		//$httpClient = new Zend_Http_Client();
		//$adapter = new Zend_Http_Client_Adapter_Curl();
		//$adapter->setCurlOption(CURLOPT_ENCODING, 'gzip');
		//$httpClient->setAdapter($adapter);
		
		
		//Zend_Rest_Client::setHttpClient($httpClient);
		
		//
		
		die();
	}
	
}
?>
