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
	}
	
	public function widgetAction()
	{
		
	}
}
?>
