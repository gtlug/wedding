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
class Photos_AdminController extends Photos_Controller_Abstract
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
	
	public function getFrob()
	{
		$cacheId = 'frob';
		if(false === ($frob = $this->_cache->load($cacheId)))
		{
			$frob = $this->_flickr->getFrob($this->_secret);
			$this->_cache->save($frob, $cacheId);
		}
		return $frob;
	}
	
	public function xmlAction()
	{
		header("content-type: text/plain");

		//$xml = "<apple>zzzz</apple>"; 
		$xml = file_get_contents('/home/NiX0n/workspace/wedding/tmp/flickr.xml');
		//print $xml;
		$dom = new DOMDocument();
		//$dom = DOMDocument::loadXML($xml);
		
        print $dom->loadXML($xml) ? "success\n" : "failure\n";
        $xpath = new DOMXPath($dom);

        $photos = $xpath->query('//photos')->item(0);
        //print_r($dom->saveXML($photos));
        
        $data = array();
        foreach(array('page', 'pages', 'perpage', 'total') as $attrib)
        {
        	$data[$attrib] = $photos->getAttribute($attrib);
        }
        print_r($data);
        
        //$results = new Zend_Service_Flickr_ResultSet($dom, $this->_flickr);
        //print_r($results);
        die("\nFINISHED!");
	}
}
?>