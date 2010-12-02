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
	
	/**
	 * 
	 * @param Zend_Db_Table_Row|integer $invite
	 * @param Zend_Db_Table_Select $select
	 * @return Zend_Db_Table_Select
	 */
	public function selectByInvite($invite, $select = null)
	{
		if(null === $select)
		{
			$select = $this->select();
		}
		$inviteId = $invite;
		if(!is_numeric($invite))
		{
			$inviteId = $invite->inviteId;
		}
		
		$select = $select->where('inviteId = ?', (integer)$inviteId);
		
		return $select;
	}
	
	/**
	 * 
	 * @param Zend_Db_Table_Row|integer $invite
	 * @param Zend_Db_Table_Select $select
	 * @return Zend_Db_Table_Rowset
	 */
	public function fetchByInvite($invite, $select = null)
	{
		$select = $this->selectByInvite($invite, $select);
		$rows = $this->fetchAll($select);
		return $rows;
	}
		
}
