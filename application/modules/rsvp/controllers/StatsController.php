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
		
		// This inner select collects inviteIds (and guests for laziness)
		// that have at least one guest that has its dateAdded <> dateUpdated.
		// We consider that invite to be RSVP-ed.
		$innerSelect = $invitesTable
			->select()
			// we're grabbing guests here, just so we don't have to
			// do another join in the outer select
			->from($invitesName, array('inviteId', 'guests'))
			->joinInner(
				$guestsName,
				"$invitesName.inviteId = $guestsName.inviteId", 
				array()
			)
			->where("$guestsName.dateAdded <> $guestsName.dateUpdated")
			// group to eliminate dupes in the outer select
			->group('inviteId')
			->group('guests')
		;
		$select = $guestsTable
			->select()
			->from($guestsName)
			// by default, only guests table columns are allowed
			->setIntegrityCheck(false)
			->joinLeft(
				// this is how we sub-select.  note we're aliasing the select
				// the same name as the invites table, just for simplicity sake
				array($invitesName => new Zend_Db_Expr("($innerSelect)")), 
				"$guestsName.inviteId = $invitesName.inviteId",
				// we want the # of guests expecting
				// we use this number to determine where we are in respect to
				// projected RSVPs
				array('guests')
			)
			// collect guests associated with the sub-select
			->where("$invitesName.inviteId IS NOT NULL")
			// also collect ad-hoc guests
			->orWhere("$guestsName.inviteId IS NULL")
		;
		$guests = $guestsTable->fetchAll($select);
		
		$foods = $this->fetchFoods();
		
		
		$this->view->guests = $guests;
		$this->view->foods = $foods;
	}
}
?>