<?php
/**
 * User: raccoon
 * Date: 29.01.12
 * Time: 22:16
 */

class UsermanagementController extends \ZF\Controller\Managment
{
    /**
     * Show user listK
     * @return void
     */
    public function listAction()
    {
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $list = $em->getRepository('\ZF\Entities\User')->findByIsDeleted(false);
        
        $this->view->list = new \ZF\View\ListView($this->getRequest()->getControllerName(),
                                                    array("id"=>"№", "Nickname"=>"Nickname", "FullName"=>"Full name", "#edit"=>"Edit", "#delete"=>"Delete"),
                                                    $list);
        $this->view->list->setTitle("Users list");

        $this->view->content = $this->view->render('management/list.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Edit user
     * @return void
     */
    public function editAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$user = $em->find('\ZF\Entities\User', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $this->view->form = new \Application_Form_User(null, "edit");
	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                //if aclrole be changed, get new role, and set it for user
                if ((int)$this->_getParam("aclrole") != $user->getAclrole()->getId())
                {
                    $AclRole = $em->find('\ZF\Entities\AclRole', (int)$this->_getParam("aclrole"));
                    $user->setAclRole($AclRole);
                }
                
                $user->setNickname($this->_getParam("nickname"));
                $user->setFirstName($this->_getParam("first_name"));
                $user->setLastName($this->_getParam("last_name"));

                if ( is_null($em->flush()) )
                {
                     $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
                }
            }
    		else
    		{
    			$this->view->form->populate($this->_request->getPost());
    		}
        }

        $this->view->form->populate(array('nickname'=>$user->getNickname(),
                                            'email'=>$user->getEmail(),
                                            'aclrole'=>$user->getAclrole()->getId(),
                                            'first_name'=>$user->getFirstname(),
                                            'last_name'=>$user->getLastname()
                                    ));
        $this->view->title = $this->view->translate('Edit user');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Add new user
     * @return void
     */
    public function addAction()
    {
        $this->view->form = new \Application_Form_User(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
    			$User = new \ZF\Entities\User();
                $em = \Zend_Registry::get('doctrine')->getEntityManager();
                $AclRole = $em->getRepository('\ZF\Entities\AclRole')->find((int)$this->_getParam("aclrole"));

                $password = \ZF\Auth\Plugin::generatePassword(8);
                $User->setEmail($this->_getParam("email"));
                $User->setNickname($this->_getParam("nickname"));
                $User->setAclRole($AclRole);
                $User->setFirstName($this->_getParam("first_name"));
                $User->setLastName($this->_getParam("last_name"));
                $User->setPassword($password);
                
                $em->persist($User);
                if ( is_null($em->flush()) )
                {
                    $SiteName = $em->getRepository('\ZF\Entities\Info')->findOneByInfoKey("SiteName")->getInfoValue();
                    $SiteEmail = $em->getRepository('\ZF\Entities\Info')->findOneByInfoKey("SiteEmail")->getInfoValue();
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

    /**
     * Delete user from list
     * @return void
     */
    public function deleteAction()
    {
        $entity = \Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\User');
        //Spacial action to user entity, in other controllers use parent::delete($entity);
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        if ( !$object = $entity->findOneById($ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $object->setEmail($object->getEmail() . "*deleted*");
        $object->setNickname($object->getNickname() . "*deleted*");
        $object->setIsDeleted(true);
        $em->flush();
        $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
    }
}