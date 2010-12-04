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
	
	/**
	 * 
	 * @param array $names
	 * @param Zend_Db_Table_Select $select
	 * @return Zend_Db_Table_Select
	 */
	public function selectByNames($names, $select = null)
	{
		if(null === $select)
		{
			$select = $this->select();
		}
		$first = true;
		foreach($names as $name)
		{
			$name = trim($name, "% \t\n\r\x0B\0");
			$name = "%$name%";
			$where = $first ? 'where' : 'orWhere';
			$select = $select->$where('names LIKE ?', $name);
			$first = false;
		}
		
		return $select;
	}
	
	/**
	 * 
	 * @param array $names
	 * @param Zend_Db_Table_Select $select
	 * @return Zend_Db_Table_Rowset
	 */
	public function fetchByNames($names, $select = null)
	{
		$select = $this->selectByNames($names, $select);
		$rows = $this->fetchAll($select);
		return $rows;
	}
	
}
