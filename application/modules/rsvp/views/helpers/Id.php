<?php
class Zend_View_Helper_Id extends Zend_View_Helper_Abstract
{
	protected $_current = 0;
	
	public function id()
	{
		return $this;
	}
	
	public function increment()
	{
		$this->_current++;
		return $this;
	}
	
	public function __toString()
	{
		return (string)$this->_current;
	}
}