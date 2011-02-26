<?php
/**
 * This abstract controller class defines functionality to be shared
 * across all module controllers.
 */
abstract class Rsvp_Controller_Abstract extends Zend_Controller_Action
{
	/**
	 * @var Wedding_Db_Table_Aliases
	 */
	protected $_aliasesTable = null;
	
	/**
	 * @var Wedding_Db_Table_Invites
	 */
	protected $_invitesTable = null;
	
	/**
	 * @var Wedding_Db_Table_Guests
	 */
	protected $_guestsTable = null;

	/**
	 * @var Wedding_Db_Table_Foods
	 */
	protected $_foodsTable = null;
	
	
	public function init()
	{
		// Place init() code you want executed
		// for every contoller in module here
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
	
	/**
	 * @return Wedding_Db_Table_Aliases
	 */
	public function aliasesTable()
	{
		if(null === $this->_aliasesTable)
		{
			$this->_aliasesTable = new Wedding_Db_Table_Aliases($this->db());
		}
		return $this->_aliasesTable;
	}
	
	/**
	 * @return Wedding_Db_Table_Invites
	 */
	public function invitesTable()
	{
		if(null === $this->_invitesTable)
		{
			$this->_invitesTable = new Wedding_Db_Table_Invites($this->db());
		}
		return $this->_invitesTable;
	}
	
	/**
	 * @return Wedding_Db_Table_Guests
	 */
	public function guestsTable()
	{
		if(null === $this->_guestsTable)
		{
			$this->_guestsTable = new Wedding_Db_Table_Guests($this->db());
		}
		return $this->_guestsTable;
	}
	
	/**
	 * @return Wedding_Db_Table_Foods
	 */
	public function foodsTable()
	{
		if(null === $this->_foodsTable)
		{
			$this->_foodsTable = new Wedding_Db_Table_Foods($this->db());
		}
		return $this->_foodsTable;
	}
	
	
	public function db()
	{
		return Zend_Registry::get('db');
	}
	
	/**
	 * @return Zend_Db_Table_Rowset
	 */
	public function fetchFoods()
	{
		$foodsTable = $this->foodsTable();
		$foods = $foodsTable->fetchAll();
		return $foods;
	}	
	
}

?>
