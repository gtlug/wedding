<?php
require_once("Abstract.php");
/**
 * The Home_IndexController
 * The name and location of this class is important.
 * "Home" is the module name
 * "Index" is the controller name
 * These two make up the first two parts of a three part
 * URI scheme.  The third part is the action name, which are
 * methods in the conroller class.
 */
class Rsvp_IndexController extends Rsvp_Controller_Abstract
{
	const PARAM_NAME = 'name';
	
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
	
	protected $_defaultGuestName = "Guest's Name";
	
	
	
	/**
	* This action can be accessed at
	* /home
	* /home/index
	* /home/index/index
	*   M     C     A
	*/
	public function indexAction()
	{
		if(!$this->getRequest()->isPost())
		{
			$this->_forward('widget');
			return;
		}
		//header("content-type: text/plain");
		
		$params = $_POST;
		
		$name = $params[self::PARAM_NAME];
		
		$invite = null;
		try
		{
			$invite = $this->_findInvite($name);
		}
		catch(Wedding_Exception $e)
		{
			//print_r($e->getMessage()); die();
		}
		
		if(!$invite)
		{
			$invite = $this->createInvite(array(
				'mailingName' => ucwords($name),
				'guests' => 1
			));
		}
		
		//print_r($invite->toArray());
		//die();
		
		$guests = $this->fetchGuests($invite);
		$foods = $this->fetchFoods();
		
		$missingGuests = $invite->guests - count($guests);
		if($missingGuests > 0)
		{
			// the resultset list is readonly
			// but we want to tack some bogus guests
			// onto it, so we'll just turn it into
			// a plain array.  We don't actually need
			// the resultset object anyways; 
			// just the results
			$guestsNew = array();
			foreach($guests as $guest) $guestsNew[] = $guest;
			$guests = $guestsNew;
			
			// for every missing guest, make a phony one
			for($n = 0; $n < $missingGuests; $n++)
			{
				$guests[] = $this->createGuest();
			}
		}
		
		$this->view->defaultGuestName = $this->_defaultGuestName;
		$this->view->invite = $invite;
		$this->view->guests = $guests;
		$this->view->foods = $foods;
	}
	
	public function widgetAction()
	{
		
	}
	
	public function createGuest(array $data = array())
	{
		$defaultData = array(
			'guestName' => $this->_defaultGuestName 
		);
		$guestsTable = $this->guestsTable();
		$guest = $guestsTable->createRow(array_merge($defaultData, $data));
		return $guest;
	}
	
	public function createInvite(array $data = array())
	{
		$defaultData = array(
		);
		$invitesTable = $this->invitesTable();
		$invite = $invitesTable->createRow(array_merge($defaultData, $data));
		return $invite;
	}
	
	
	/**
	 * 
	 * @param string $name
	 * @return array
	 */
	public function fetchAliases($name)
	{
		$aliasesTable = $this->aliasesTable();
		$aliases = $aliasesTable->fetchAliases($name);
		// if there were no aliases found, or were any legitimate ones
		// (SQL doesn't quite have robust string comparison)
		if(!$aliases || (false === array_search($name, $aliases)))
		{
			$aliases = array($name);
		}
		return $aliases;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return Zend_Db_Table_Rowset|array
	 */
	public function fetchInvites($names)
	{
		$invitesTable = $this->invitesTable();
		$invites = $invitesTable->fetchByNames($names);
		if(!count($invites))
		{
			// @todo replace with catchable exception
			throw new Wedding_Exception("No invites found");
		}
		return $invites;
	}
	
	/**
	 * 
	 * @param Zend_Db_Table_Row $invite
	 * @return Zend_Db_Table_Rowset
	 */
	public function fetchGuests($invite)
	{
		$guestsTable = $this->guestsTable();
		$guests = $guestsTable->fetchByInvite($invite);
		return $guests;
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
	 * 
	 * @param string $name
	 * @return Zend_Db_Table_Row
	 */
	protected function _findInvite($name)
	{
		// Find all names.
		// Ignore wierd characters
		// Since they might allow sql injection
		$regex = "/\w+/i";
		if(!preg_match_all($regex, $name, $nameParts))
		{
			throw new Exception("Bad form");
		}
		$nameParts = $nameParts[0];
		// now $nameParts is an array of each of the names [first, last, etc]
		
		// multiple plurailty on purpose (list of lists)
		// separated by name parts
		$aliaseses = array();
		// this second array is similar to the first, 
		// but all name parts are treated equal
		// this is what is passed to fetchInvites()
		$aliasesAll = array();
		foreach($nameParts as $namePart)
		{
			$aliases = $this->fetchAliases($namePart);
			$aliaseses[] = $aliases;
			$aliasesAll = array_merge($aliasesAll, $aliases);
		}
		
		// invites contains every conceivable invite
		// that remotely looks like what we're looking for
		$invites = $this->fetchInvites($aliasesAll);

		// counts keeps track of how many names match
		// for each invite.  The invite with the highest
		// count wins the disambiguation.
		$counts = array();
		
		foreach($invites as $i=>$invite)
		{
			// start with zero,
			// so we can increment later
			$counts[$i] = 0;
			foreach($aliaseses as $aliases)
			{
				// each set of $aliases represent
				// one part of the name (first, last, etc.)
				foreach($aliases as $alias)
				{
					// find this whole word
					// don't match part of one
					// ignore case
					$regex = "/\b$alias\b/i";
					if(preg_match($regex, $invite->names))
					{
						// increment the count
						$counts[$i]++;
						// we're done searching for this part of the name
						break;
					}
				}
			}
		}
		
		// sort keeping index the same
		// the indexes are what invite we're using
		asort($counts);
		// make the first one the largest
		$counts = array_reverse($counts, true);

		// the foreach is a bit unnecessary,
		// but it's the easiest way to get the first element
		foreach($counts as $i=>$count)
		{
			// you have to match at least two names
			// before we consider it valid
			if($count < 2)
			{
				throw new Wedding_Exception("No valid invites found");
			}
			return $invites[$i];
		}
	}
}
?>
