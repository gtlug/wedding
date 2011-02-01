<?php
class Wedding_Db_Table_Foods extends Zend_Db_Table_Abstract
{
	protected $_name = 'foods';
	
	protected $_primary = 'foodId';
	
	protected $_sequence = true;
	        
	protected $_dependentTables = array('Wedding_Db_Table_Guests');
	
	protected $_referenceMap    = array(
	);
}
