<?php
	/*
	 * Bootstrap File
	 * NOTE: ORDER IS IMPORTANT!
	 */

	//header("content-type: text/plain");
	if(isset($_SERVER['INCLUDE_PATH']))
		set_include_path($_SERVER['INCLUDE_PATH']);

	// This class includes a lot of useful classes,
	// and basically sets us up to do everything Zend from here on in
	require_once 'Zend/Controller/Front.php';

	// allow lazy class loading
	// so class files don't have to be manually included
	$autoloader = Zend_Loader_Autoloader::getInstance();
	$autoloader->registerNamespace('Demo_');

	// Force all errors to throw Exceptions
	/*function exception_error_handler($errno, $errstr, $errfile, $errline ) 
	{
		if($errno < E_WARNING)
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
	set_error_handler("exception_error_handler");*/
	
	// $site_root represents the base directory where your application, wroot, and other folders reside.
	$site_root = realpath(isset($_SERVER['SITE_ROOT'])?$_SERVER['SITE_ROOT']:$_SERVER['DOCUMENT_ROOT'] . "/../") . "/";
	Zend_Registry::set('site_root',$site_root);
		
	// setup error logger
	$logger = new Zend_Log(new Zend_Log_Writer_Stream($site_root. 'var/log/error.log'));
	Zend_Registry::set('logger',$logger);
	
	// load static configs and put in registry
	$siteconfig = new Zend_Config_Ini($site_root. 'etc/siteconfig.ini', 'site');
	$dbconfig = new Zend_Config_Ini($site_root. 'etc/dbconfig.ini', $siteconfig->site->runmode);
	Zend_Registry::set('siteconfig',$siteconfig);
	Zend_Registry::set('dbconfig',$dbconfig);
	
	// setup database singletons (so the session handlers can use one of them) 
	$db = Zend_Db::factory($dbconfig->db->adapter, $dbconfig->db->config->toArray());

	// check connections before we proceed
	try 
	{
	    $db->getConnection();
	}
	catch (Zend_Db_Adapter_Exception $e) 
	{
		$logger->log(print_r($e,true), Zend_Log::NOTICE);
	    // perhaps a failed login credential, or perhaps the RDBMS is not running
	}
	
	Zend_Registry::set('db',$db);
	

	// assemble session namespaces
	try
	{
		$session = new Zend_Session_Namespace('default'); 
	}
	catch(Exception $e)
	{
		$logger->log(print_r($e,true), Zend_Log::EMERG);
		return;
	}
	Zend_Registry::set('session',$session);


	//
	// Now that we've got all the infrastructure stuff done,
	// we can get to more usefull things, like loading the 
	// appropriate pages
	//
	//Zend_Controller_Front::run('/path/to/app/controllers');
	$appication_root = $site_root. 'application/';
	Zend_Registry::set('application_root', $appication_root);
	
	// Setup view object
	// We do this manually so that we can 
	// add our own class paths and whatnot;
	// otherwise, ZF would set this up automatically
	$view = new Zend_View();
	// we've got our own shared view helpers here
	$view->addHelperPath('Demo/View/Helper/', 'Demo_View_Helper_');
	// add a shared views folder
	$view->addBasePath($appication_root . 'views');
	Zend_Registry::set('view',$view);

	// $front will do most of the heavy lifting
	// in regards to routing human friendly URLs
	// to the proper module/controller/action
	$front = Zend_Controller_Front::getInstance();
	$front->addModuleDirectory($appication_root . 'modules');
	$front->addControllerDirectory($appication_root . 'controllers', 'default');
	$front->setParam('view', $view);
	//$front->setParam('useDefaultControllerAlways', true);
	
	// by default, view renderer uses .phtml as the suffix.  we're gonna change this
	// because we want an extension that better reflects the content type.
	// in this case, it'll be html, and we want our IDE to treat it like a PHP file
	// so it'll end in PHP
	$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view, array(
		'viewSuffix' => 'html.php'
	));
	Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	
	// we aren't actually using this anywhere, but ContextSwitch is
	// something to look into if you plan on sending data out in 
	// different formats (JSON, XML, CSS, etc.)
	$contexts = new Zend_Config_Ini('../etc/contexts.ini');
	$contextSwitch = new Zend_Controller_Action_Helper_ContextSwitch();
	$contextSwitch->setContexts($contexts->toArray());
	Zend_Controller_Action_HelperBroker::addHelper($contextSwitch);
	
	// Zend_Layout is a fantastic package
	// It allows your views to render within a shared layout
	// It also leverages PHP output buffering so that your content
	// is rendered before the layout is.
	Zend_Layout::startMvc(array(
		'layout'		=> 'default',
		'layoutPath'	=> $appication_root . 'layouts',
		'viewSuffix'	=> 'html.php'
	));
	$view = null;
	$view = Zend_Layout::getMvcInstance()->getView();
	// also give layout view access to shared view helpers
	$view->addHelperPath('Demo/View/Helper/', 'Demo_View_Helper_');
	Zend_Layout::getMvcInstance()->setView($view);
	
	// add layout variables from our siteconfig file
	$layout_defaults = new Zend_Config_Ini($site_root. 'etc/siteconfig.ini', 'layout');
	foreach($layout_defaults->toArray() as $key=>$val)
		Zend_Layout::getMvcInstance()->$key = $val;
	
	// Dispatch our application
	try
	{
		$response = $front->dispatch();
	}
	catch(Exception $e)
	{
		$logger->log(print_r($e,true), Zend_Log::ALERT);
		header('Location: /error');
		return;
	}
	
	// Peform any last tasks prior to sending response to client


	
	// Finally send the response
	print $response;
?>
