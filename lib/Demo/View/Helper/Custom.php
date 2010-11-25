<?php
class Demo_View_Helper_Custom
{
	/**
	* @var string
	*/
	protected $_myString = "this is my custom response";

	public function custom($myString = null)
	{
		if(null !== $myString)
			$this->_myString = (string) $myString;
		return $this;
	}

	public function __toString()
	{
		return $this->_myString;
	}
}
?>
