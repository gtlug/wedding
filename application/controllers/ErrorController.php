<?php
require_once("Abstract.php");
/**
 * The ErrorController handles any uncaught exceptions
 * once the application has been dispatched.
 * It is also triggered when a page (module/controller/action)
 * could not be found. 
 *
 * This allows for a graceful error page.
 */
class ErrorController extends Controller_Abstract
{
	public function indexAction()
	{
		if(null !== ($errors = $this->_getParam('error_handler')))
		{
			switch ($errors->type) 
			{
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
					// 404 error -- controller or action not found
					$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
			
					// ... get some output to display...
				break;
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
				default:
					// application error; display error page, but don't change
					// status code
			
					// ...
			
					// Log the exception:
					$e = $errors->exception;
					Zend_Registry::get('logger')->log($e->getMessage(), Zend_Log::NOTICE);
				break;
			}
		}
	}
}
?>
