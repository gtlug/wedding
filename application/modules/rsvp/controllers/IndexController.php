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
		}
		header("content-type: text/plain");
		
		$params = $_POST;
		
		$name = $params[self::PARAM_NAME];
		
		$invite = $this->_findInvite($name);
		
		print_r($invite->toArray());
		die();
	}
	
	public function widgetAction()
	{
		
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
		if(!$aliases)
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
			//throw new Exception("No invites found");
			die("No invites found");
		}
		return $invites;
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
		
		// if there's only one, there's
		// no need for disambiguation
		if(count($invites) == 1)
		{
			return $invites[0];
		}
		
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
		$counts = array_reverse($counts);
		
		// the foreach is a bit unnecessary,
		// but it's the easiest way to get the first element
		foreach($counts as $i=>$count)
		{
			return $invites[$i];
		}
	}
}
?>
