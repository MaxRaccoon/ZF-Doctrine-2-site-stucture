<?php
/**
 * User: raccoon
 * Date: 01.02.12 23:31
 */
 
class NewsmanagementController extends Zend_Controller_Action
{
    /**
     * Show news list
     * @return void
     */
    public function listAction()
    {
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $list = $em->getRepository('\ZF\Entity\News')->findAll();

        $this->view->list = new \ZF\View\ListView($this->getRequest()->getControllerName(),
                                                    array("id"=>"№", "Title"=>"Title", "Anons"=>"Anons", "#edit"=>"Edit"),
                                                    $list);
        $this->view->list->setTitle("Users list");

        $this->view->content = $this->view->render('management/list.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    public function addAction()
    {
        $this->view->form = new \Application_Form_News(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
    			$News = new \ZF\Entity\News();
                $em = \Zend_Registry::get('doctrine')->getEntityManager();

                $News->setTitle($this->_getParam("title"));
                $News->setAnons($this->_getParam("anons"));
                $News->setAclRole($AclRole);
                $News->setFirstName($this->_getParam("first_name"));
                $User->setLastName($this->_getParam("last_name"));
                $User->setPassword($password);

                $em->persist($User);
                if ( is_null($em->flush()) )
                {
                    $SiteName = $em->getRepository('\ZF\Entity\Info')->findOneByInfoKey("SiteName")->getInfoValue();
                    $SiteEmail = $em->getRepository('\ZF\Entity\Info')->findOneByInfoKey("SiteEmail")->getInfoValue();

                    $message = sprintf($this->view->translate("Hello, %s!"), $User->getNickname()) . "<br /><br />"
                               . sprintf( $this->view->translate("On site %s has been created account for you."), '<a href="' . $this->getFrontController()->getBaseUrl() . '">' . $this->view->translate("Site Name") . '</a>' ) . "<br />"
                               . $this->view->translate("Your login") . ": {$User->getNickname()}<br />"
                               . $this->view->translate("Your password") . ": {$password}<br /><br />"
                               . $this->view->translate("Good bye");

                    $mail = new Zend_Mail('windows-1251');
                    $mail->setBodyHtml( iconv("UTF-8", "Windows-1251",  $message) );
                    $mail->setFrom($SiteEmail, iconv('utf8', 'cp1251', $SiteName ));
                    $mail->addTo($User->getEmail(), iconv('utf8', 'cp1251', $User->getFullName() ));
                    $mail->setSubject( iconv('UTF-8', 'Windows-1251', sprintf($this->view->translate("An «%s» account has been created for you!"), $SiteName) ) );
                    $mail->send();

                    $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
                }
    		}
    		else
    		{
    			$this->view->form->populate($this->_request->getPost());
    		}
    	}

        $this->view->title = $this->view->translate('Add new user');
        $this->view->content = $this->view->render('management/edit.phtml');        
        \ZF\View\ViewPlugin::setNoRender();
    }    

    public function editAction()
    {
        
    }
}