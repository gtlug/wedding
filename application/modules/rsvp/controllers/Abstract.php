<?php
/**
 * This abstract controller class defines functionality to be shared
 * across all module controllers.
 */
abstract class Rsvp_Controller_Abstract extends Zend_Controller_Action
{
	public function init()
	{
		// Place init() code you want executed
		// for every contoller in module here
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
