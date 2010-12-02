<?php
class Wedding_Db_Table_Aliases extends Zend_Db_Table_Abstract
{
	protected $_name = 'aliases';
	
	protected $_primary = 'aliasId';
	
	protected $_sequence = true;
	        
	protected $_dependentTables = array('Wedding_Db_Table_Invites');
	
	protected $_referenceMap    = array(
	);
}
