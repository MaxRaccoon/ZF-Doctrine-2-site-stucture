<?php
/**
 * User: raccoon
 * Date: 16.02.12 14:02
 */
class PagemanagementController extends \ZF\Controller\Managment
{
    /**
     * Show page list
     * @return void
     */
    public function listAction()
    {
        $entity_name = '\ZF\Entities\Page';
        $titles = array("id"=>"№", "Title"=>"Title", "Url"=>"Url", "#edit"=>"Edit", "#delete"=>"Delete");
        $title = "Page list";
        parent::showList($entity_name, $titles, $title);
    }

    /**
     * Add page
     * @return void
     */
    public function addAction()
    {
        $this->view->form = new \Application_Form_Page(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $em = \Zend_Registry::get('doctrine')->getEntityManager();
                $auth = \Zend_Auth::getInstance();
                $user = $em->getRepository('\ZF\Entities\User')->findOneById($auth->getIdentity()->getId());

    			$Page = new \ZF\Entities\Page();
                $Page->setTitle($this->_getParam("title"));
                $Page->setUrl($this->_getParam("url"));
                $Page->setText($this->_getParam("text"));
                $Page->setMetaTags($this->_getParam("meta_tags"));
                $Page->setAuthor($user);
                $Page->setDtCreate(new \DateTime());
                $Page->setDtUpdate(new \DateTime());

                $em->persist($Page);
                if ( is_null($em->flush()) )
                {
                    //Add tags
                    \ZF\Controller\Tags::addRelations($Page, $this->_getParam("tags"));
                    $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
                }
    		}
    		else
    		{
    			$this->view->form->populate($this->_request->getPost());
    		}
    	}

        $this->view->headScript()->prependFile('/js/generate_url.js');
        $this->view->title = $this->view->translate('Add page');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Edit page
     * @return void
     */
    public function editAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Page = $em->find('\ZF\Entities\Page', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $this->view->form = new \Application_Form_Page(null, "edit");
	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $auth = \Zend_Auth::getInstance();
                $user = $em->getRepository('\ZF\Entities\User')->findOneById($auth->getIdentity()->getId());

                $Page->setTitle($this->_getParam("title"));
                $Page->setUrl($this->_getParam("url"));
                $Page->setText($this->_getParam("text"));
                $Page->setMetaTags($this->_getParam("meta_tags"));
                $Page->setAuthor($user);
                $Page->setDtUpdate(new \DateTime());

                if ( is_null($em->flush()) )
                {
                    //Edit tags
                    \ZF\Controller\Tags::editRelations($Page, $this->_getParam("tags"));
                    $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
                }
            }
    		else
    		{
    			$this->view->form->populate($this->_request->getPost());
    		}
        }

        $this->view->form->populate(array('title'=>$Page->getTitle(),
                                            'url'=>$Page->getUrl(),
                                            'text'=>$Page->getText(),
                                            'meta_tags'=>$Page->getMetaTags(),
                                            'tags'=>$em->getRepository('\ZF\Entities\PageTagRel')->getTagsInString($Page)
                                    ));
        $this->view->title = $this->view->translate('Edit page');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Delete page from list
     * @return void
     */
    public function deleteAction()
    {
        $entity = \Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\Page');
        $relation = \Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\PageTagRel');
        return parent::delete($entity, $relation);
    }      
}