<?php
class Wedding_Db_Table_Guests extends Zend_Db_Table_Abstract
{
	protected $_name = 'guests';
	
	protected $_primary = 'guestId';
	
	protected $_sequence = true;
	        
	protected $_dependentTables = array();
	
	protected $_referenceMap    = array(
		'Wedding_Db_Table_Invites' => array(
			'columns'           => 'inviteId',
			'refTableClass'     => 'Wedding_Db_Table_Invites',
			'refColumns'        => 'inviteId'
		)
	);
}
