<?php
class Wedding_Db_Table_Invites extends Zend_Db_Table_Abstract
{
	protected $_name = 'invites';
	
	protected $_primary = 'inviteId';
	
	protected $_sequence = true;
	        
	protected $_dependentTables = array('Wedding_Db_Table_Guests');
	
	protected $_referenceMap    = array(
		'Wedding_Db_Table_Aliases' => array(
			'columns'           => 'names',
			'refTableClass'     => 'Wedding_Db_Table_Aliases',
			'refColumns'        => 'aliases'
		)
	);
}
