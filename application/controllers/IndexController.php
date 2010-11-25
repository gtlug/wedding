<?php
require_once("Abstract.php");
/**
 * Default Index controller
 * This conroller/module is used when 
 * no module is specified in the URL.
 *
 * Most commonly, it is loaded when there is nothing
 * in the URI.  As in, when a user first comes to the site
 * (i.e. http://www.example.com/)
 */
class IndexController extends Controller_Abstract
{
	public function indexAction()
	{
		// send them somewhere more useful
		$this->_redirect('/home');
	}
}
?>
