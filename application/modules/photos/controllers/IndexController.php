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
	
	public function indexAction()
	{
		$tag = $this->_getParam('tag', 'whitevanberlowedding');

		Zend_Paginator::setCache($this->_cache);
		$cacheId = "paginator_$tag";
		if(false === ($paginator = $this->_cache->load($cacheId)))
		{
			if(!$this->_auth->hasIdentity())
			{
				throw new Exception("Not Authenticated");
			}
			$auth = $this->_auth->getIdentity();
			$user = $auth->getUser();
			$this->_flickr->token = $auth->getToken();
			$options = array(
				'tags' => $tag,
				'user_id' => $user['nsid'],
				'auth_token' => $auth->getToken()
			);
			$paginator = new Zend_Paginator(new Gtwebdev_Paginator_Adapter_Flickr($this->_flickr, $options));
			
			$this->_cache->save($paginator, $cacheId, array('paginator'));
		}

		$paginator->setDefaultScrollingStyle('Sliding');
		$paginator->setItemCountPerPage((integer)$this->_getParam('per', 12));
		$paginator->setCurrentPageNumber((integer)$this->_getParam('page', 1));
		$paginator->setCacheEnabled(true);
	
		
		$this->view->photos = $paginator;
	}
	
	public function widgetAction()
	{
		// same logic; different view
		$this->indexAction();
	}
	
	/**
	* This action can be accessed at
	* /home
	* /home/index
	* /home/index/index
	*   M     C     A
	*/
	public function testAction()
	{
		$NiX0n = "74154383@N00";
		
		if(!$this->_auth->hasIdentity())
		{
			throw new Exception("Not Authenticated");
		}
		$auth = $this->_auth->getIdentity();
		$this->_flickr->token = $auth->getToken();
		$results = $this->_flickr->privateTagSearch(
			'fragfest12', 
			//'whitevanberlowedding',
			$this->_secret,
			array(
				'user_id' => $NiX0n,
				'page'=>2,
				'auth_token' => $auth->getToken()
			)
		);
		$this->view->photos = $results;
	}
	
}
?>
