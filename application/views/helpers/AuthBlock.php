<?php
class Application_View_Helper_AuthBlock extends Zend_View_Helper_Action
{

	public function AuthBlock()
	{
		$view = $this->cloneView();
		if (Zend_Auth::getInstance()->hasIdentity())
		{
	    	$storage = new Zend_Auth_Storage_Session();
	    	$view->user = $storage->read();
			return $view->render('UserBlock.phtml');
		}
        else
        {
            return $view->render('AuthBlock.phtml');
        }
	}

}