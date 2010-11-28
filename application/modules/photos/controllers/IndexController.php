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
class Photos_IndexController extends Photos_Controller_Abstract
{
	public function init()
	{
		parent::init();
	}
	
	/**
	* This action can be accessed at
	* /home
	* /home/index
	* /home/index/index
	*   M     C     A
	*/
	public function indexAction()
	{
		$NiX0n = "74154383@N00";
		
		if(!$this->_auth->hasIdentity())
		{
			throw new Exception("Not Authenticated");
		}
		$auth = $this->_auth->getIdentity();
		$this->_flickr->token = $auth->getToken();
		$results = $this->_flickr->privateTagSearch(
			//'fragfest12', 
			'whitevanberlowedding',
			$this->_secret,
			array(
				'user_id' => $NiX0n,
				'auth_token' => $auth->getToken()
			)
		);
		$this->view->photos = $results;
	}
	
}
?>
