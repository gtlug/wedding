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
class Rsvp_StatsController extends Rsvp_Controller_Abstract
{
	
	
	/**
	* This action can be accessed at
	* /home
	* /home/index
	* /home/index/index
	*   M     C     A
	*/
	public function indexAction()
	{
		$guestsTable = $this->guestsTable();
		$invitesTable = $this->invitesTable();
		$invitesName = $invitesTable->info(Zend_Db_Table::NAME);
		$guestsName = $guestsTable->info(Zend_Db_Table::NAME);
		$select = $guestsTable
			->select()
			->from($guestsName)
			// by default, only guests table columns are allowed
			->setIntegrityCheck(false)
			->joinLeft(
				$invitesName, 
				"$guestsName.inviteId = $invitesName.inviteId",
				// we want the # of guests expecting 
				array('guests')
			)
			->where("$guestsName.dateAdded <> $guestsName.dateUpdated")
			->orWhere("$guestsName.inviteId IS NULL")
		;
		$guests = $guestsTable->fetchAll($select);
		
		$foods = $this->fetchFoods();
		
		
		$this->view->guests = $guests;
		$this->view->foods = $foods;
	}
}
?>