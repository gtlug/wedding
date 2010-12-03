<?php
class Wedding_Db_Table_Aliases extends Zend_Db_Table_Abstract
{
	protected $_name = 'aliases';
	
	protected $_primary = 'aliasId';
	
	protected $_sequence = true;
	        
	protected $_dependentTables = array('Wedding_Db_Table_Invites');
	
	protected $_referenceMap    = array(
	);
	
	/**
	 * 
	 * @param string $alias
	 * @param Zend_Db_Table_Select $select
	 * @return Zend_Db_Table_Select
	 */
	public function selectAliases($alias, $select = null)
	{
		if(null === $select)
		{
			$select = $this->select();
		}
		
		$alias = trim($alias, "% \t\n\r\x0B\0");
		$alias = "%$alias%";
		$select = $select->where('aliasList LIKE ?', $alias);
		
		return $select;
	}
	
	/**
	 * 
	 * @param string $alias
	 * @param Zend_Db_Table_Select $select
	 * @return array
	 */
	public function fetchAliases($alias, $select = null)
	{
		$select = $this->selectAliases($alias, $select);
		$aliasesRows = $this->fetchAll($select);
		$aliases = array();
		foreach($aliasesRows as $aliasesRow)
		{
			$aliases = array_merge(
				$aliases, 
				preg_split("/\s*,\s*/", $aliasesRow->aliasList)
			);
		}
		
		return $aliases;
	}
	
	
}
