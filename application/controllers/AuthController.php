<?php
class AuthController extends Zend_Controller_Action
{
    function init()
    {
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();
    }

    function indexAction()
    {
        $this->_redirect('/');
    }

    function loginAction()
    {
    	if (Zend_Auth::getInstance()->hasIdentity()) {
	    	return $this->_redirect('/');
	    }

    	$LoginForm = new Application_Form_Login();
    	if($this->_request->isPost())
    	{
    		$formData = $this->_request->getPost();
    		if( $LoginForm->isValid($this->_request->getPost()) )
    		{
    			$adapter = new ZF\Auth\DoctrineAuthAdapter($this->_getParam("username"), $this->_getParam("password"));
    			$result = Zend_Auth::getInstance()->authenticate($adapter);

    			if (Zend_Auth::getInstance()->hasIdentity())
    			{
    				$this->_redirect('/');
    			}
    			else
    			{
    				$this->view->message = implode('  ', array_map(array($this->view, 'translate'), $result->getMessages()));
    			}
    		}
    		else
    		{
    			$LoginForm->populate($formData);
    		}
    	}

        $this->view->PageTitle = $this->view->translate('Login');
        $this->view->form = $LoginForm;
        $this->view->content = $this->view->render('auth/login.phtml');
    }

    public function secretAction()
    {
    	$this->_redirect('/');
    }

    public function logoutAction()
    {
		$auth = Zend_Auth::getInstance();
	    $auth->clearIdentity();
	    Zend_Session::forgetMe();
	    $this->_redirect('/');
    }
}

