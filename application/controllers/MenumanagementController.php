<?php
/**
 * User: raccoon
 * Date: 24.02.12 16:22
 */
class MenumanagementController extends \ZF\Controller\Managment
{
    /**
     * Show menu list
     * @return void
     */
    public function listAction()
    {
        $entity_name = '\ZF\Entities\Menu';
        $titles = array("id"=>"№", "Title"=>"Title", "#url"=>"Link", "#moveup"=>"Move up", "#movedown"=>"Move down", "#edit"=>"Edit", "#delete"=>"Delete");
        $title = "News list";
        parent::showList($entity_name, $titles, $title, false);
    }

    /**
     * Add menu
     * @return void
     */
    public function addAction()
    {
        $this->view->form = new \Application_Form_Menu(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $em = \Zend_Registry::get('doctrine')->getEntityManager();
                $controller = $em->getRepository('\ZF\Entities\AclController')->findOneById($this->_getParam("aclcontroller"));
                $action = $em->getRepository('\ZF\Entities\AclAction')->findOneById($this->_getParam("aclaction"));

    			$Menu = new \ZF\Entities\Menu();
                $Menu->setTitle($this->_getParam("title"));
                $Menu->setAclController($controller);
                $Menu->setAclAction($action);
                $Menu->setRoute($this->_getParam("route"));
                $Menu->setMethod($this->_getParam("method"));
                $Menu->setPosition( $em->getRepository('\ZF\Entities\Menu')->getLastPosition() + 1 );

                $em->persist($Menu);
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

        $this->view->title = $this->view->translate('Add menu');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Edit menu
     * @return void
     */
    public function editAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Menu = $em->find('\ZF\Entities\Menu', (int)$ID) )
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $this->view->form = new \Application_Form_Menu(null, "edit");
	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $controller = $em->getRepository('\ZF\Entities\AclController')->findOneById($this->_getParam("aclcontroller"));
                $action = $em->getRepository('\ZF\Entities\AclAction')->findOneById($this->_getParam("aclaction"));

                $Menu->setTitle($this->_getParam("title"));
                $Menu->setAclController($controller);
                $Menu->setAclAction($action);
                $Menu->setRoute($this->_getParam("route"));
                $Menu->setMethod($this->_getParam("method"));
                
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

        $this->view->form->populate(array('title'=>$Menu->getTitle(),
                                            'aclcontroller'=>$Menu->getAclController()->getId(),
                                            'aclaction'=>$Menu->getAclAction()->getId(),
                                            'route'=>$Menu->getRoute()
                                    ));
        $this->view->title = $this->view->translate('Edit menu');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Delete menu from list
     * @return void
     */
    public function deleteAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Menu = $em->find('\ZF\Entities\Menu', (int)$ID) )
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $em->getRepository('\ZF\Entities\Menu')->removeMenu($Menu);
        $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
    }

    /**
     * @return void
     */
    public function moveupAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Menu = $em->find('\ZF\Entities\Menu', (int)$ID) )
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        if (!$moveDownMenu = $em->getRepository('\ZF\Entities\Menu')->getByPosition( ($Menu->getPosition() - 1) ))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $moveDownMenu->setPosition($Menu->getPosition());
        $Menu->setPosition($Menu->getPosition() - 1);
        $em->flush();
        $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
    }

    /**
     * @return void
     */
    public function movedownAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Menu = $em->find('\ZF\Entities\Menu', (int)$ID) )
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        if (!$moveUpMenu = $em->getRepository('\ZF\Entities\Menu')->getByPosition( ($Menu->getPosition() + 1) ))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $moveUpMenu->setPosition($Menu->getPosition());
        $Menu->setPosition($Menu->getPosition() + 1);
        $em->flush();
        $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
    }    
}