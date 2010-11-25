<?php
abstract class Controller_Abstract extends Zend_Controller_Action
{
	public function init()
	{
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
