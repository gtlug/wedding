<?php
/**
 * This abstract controller class defines functionality to be shared
 * across all module controllers.
 */
abstract class Photos_Controller_Abstract extends Zend_Controller_Action
{
	/**
	 * 
	 * @var Zend_Cache_Core
	 */
	protected $_cache = null;
	
	/**
	 * @var Gtwebev_Service_Flickr
	 */
	protected $_flickr = null;
	
	protected $_key = "819ec8171b5970c69180ac9121139ae3";
	
	protected $_secret = "f9f408a4ad592891";
	
	public function init()
	{
		$this->_flickr = new Gtwebdev_Service_Flickr($this->_key);
		
		$this->_cache = Zend_Cache::factory(
			// Frontend, Backend
			'Core', 'File', 
			// Frontend Options
			array(
				'automatic_serialization' => true
			),
			// Backend Options 
			array(
				'cache_dir' => Zend_Registry::get('site_root') . 'var/cache/flickr'
			)
		);
	}
	
	public function __call($method, $args)
	{
		if ('Action' == substr($method, -6)) {
			// If the action method was not found, forward to the index action
			return $this->_forward('index');
		}

		// all other methods throw an exception
		throw new Exception('Invalid method "' . $method . '" called');
	}
}

?>
