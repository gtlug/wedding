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
	const PARAM_INVITE_ID = 'inviteId';
	const PARAM_GUEST_ID = 'guestId';
	const PARAM_GUEST_NAME = 'guestName';
	const PARAM_GUEST_ATTENDING = 'attending';
	const PARAM_FOOD_ID = 'foodId';
	
	
	protected $_log = null;
	protected $_defaultGuestName = "Guest's Name";
	
	protected $_filters = array(
		self::PARAM_INVITE_ID => array('Digits'),
		self::PARAM_GUEST_ID => array('Digits'),
		self::PARAM_GUEST_NAME => array('Alnum', array('allowwhitespace'=>true)),
		self::PARAM_GUEST_ATTENDING => array('Digits'),
		self::PARAM_FOOD_ID => array('Digits')
	);
	
	public function init()
	{
		parent::init();
		$this->_log = Zend_Registry::get('logger');
		
	}
	
	
	/**
	* This action can be accessed at
	* /rsvp
	* /rsvp/index
	* /rsvp/index/index
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
		
		$name = strtolower($params[self::PARAM_NAME]);
		$this->_log->debug($name);
		
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
	
	public function doAction()
	{
		header('content-type: text/plain');
		
		$params = $_POST;
		$inviteId = $params[self::PARAM_INVITE_ID];
		$guestsTable = $this->guestsTable();
		$guestCols = $guestsTable->info(Zend_Db_Table::COLS);
		$guests = $this->invertKeys($params, $guestCols);
		$now = new Zend_Db_Expr('NOW()');
		$filterKeys = array_flip(array_keys($this->_filters));
		foreach($guests as $guest)
		{
			$guest[self::PARAM_INVITE_ID] = $inviteId;
			// strip out keys we don't have filters for
			$guest = array_intersect_key($guest, $filterKeys);
			foreach($guest as $k=>&$v)
			{
				if(!$v)
				{
					// it's easier to deal with nulls
					// than it is to filter false-equivolent values
					$v = null;
					continue;
				}
				$args = array_merge(array($v), $this->_filters[$k]);
				$v = call_user_func_array('Zend_Filter::filterStatic', $args);
			}
			$guestRow = null;
			if($guest[self::PARAM_GUEST_ID])
			{
				$guestRows = $guestsTable->find($guest[self::PARAM_GUEST_ID]);
				if(count($guestRows))
				{
					$guestRow = $guestRows[0];
				}
			}
			if(null === $guestRow)
			{
				if(!$guest[self::PARAM_GUEST_ATTENDING])
				{
					// if if the unnamed guest isn't even
					// attending, we don't care.  we won't
					// add them.
					continue;
				}
				$guestRow = $this->createGuest();
				$guest['dateAdded'] = $now;
			}
			$guest['dateUpdated'] = $now;
			
			// I have to use a different value name 
			// because there's an odd PHP bug that breaks it 
			foreach($guest as $k=>$v2)
			{
				$guestRow->{$k} = $v2;
			}
			
			$guestRow->save();
			//print_r($guestRow->toArray());
		}
		$this->_redirect('/rsvp/index/finished');
	}
	
	public function finishedAction()
	{
		
	}
	
	/**
	 * Invert keys in a two-dimensional array
	 * $array[$a][$b] => $array[$b][$a]
	 * Useful for transforming array POST data
	 * into object relational data
	 * 
	 * @param array $array
	 * @param array $keys 
	 * 		Keys to pull from $array.  
	 * 		If null, all keys in $array are used
	 * 		Non-existent keys are ignored.
	 * @return array
	 */
	public function invertKeys($array, $keys = null)
	{
		if(null === $keys)
		{
			$keys = array_keys($array);
		}
		$newArray = array();
		
		foreach($keys as $k1)
		{
			if(!isset($array[$k1]))
				continue;
			
			$v1 = $array[$k1];
			
			if(!is_array($v1)) 
				continue;
				
			foreach($v1 as $k2=>$v2)
			{
				if(!isset($newArray[$k2]))
					$newArray[$k2] = array();
					
				$newArray[$k2][$k1] = $v2;
			}
		}
		return $newArray;
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
		$this->_log->debug("aliaseses for $name: " . print_r($aliaseses, true));
		// invites contains every conceivable invite
		// that remotely looks like what we're looking for
		$invites = $this->fetchInvites($aliasesAll);
		
		$this->_log->debug("invites for $name: " . print_r($invites->toArray(), true));

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
		
		$this->_log->debug("counts for $name:" . print_r($counts, true));

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
