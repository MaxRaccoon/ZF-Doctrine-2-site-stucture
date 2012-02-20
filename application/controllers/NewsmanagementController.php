<?php
/**
 * User: raccoon
 * Date: 01.02.12 23:31
 */
 
class NewsmanagementController extends \ZF\Controller\Managment
{
    /**
     * Show news list
     * @return void
     */
    public function listAction()
    {
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $list = $em->getRepository('\ZF\Entities\News')->findByIsDeleted(false);

        $this->view->list = new \ZF\View\ListView($this->getRequest()->getControllerName(),
                                                    array("id"=>"№", "Title"=>"Title", "Anons"=>"Anons", "#edit"=>"Edit", "#delete"=>"Delete"),
                                                    $list);
        $this->view->list->setTitle("News list");

        $this->view->content = $this->view->render('management/list.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Add news
     * @return void
     */
    public function addAction()
    {
        $this->view->form = new \Application_Form_News(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $em = \Zend_Registry::get('doctrine')->getEntityManager();
                $auth = \Zend_Auth::getInstance();
                $user = $em->getRepository('\ZF\Entities\User')->findOneById($auth->getIdentity()->getId());

    			$News = new \ZF\Entities\News();
                $News->setTitle($this->_getParam("title"));
                $News->setAnons($this->_getParam("anons"));
                $News->setText($this->_getParam("text"));
                $News->setAuthor($user);
                $News->setDtCreate(new \DateTime());
                $News->setDtUpdate(new \DateTime());

                $em->persist($News);
                if ( is_null($em->flush()) )
                {
                    //Add tags
                    \ZF\Controller\Tags::addRelations($News, $this->_getParam("tags"));
                    $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
                }
    		}
    		else
    		{
    			$this->view->form->populate($this->_request->getPost());
    		}
    	}

        $this->view->title = $this->view->translate('Add news');
        $this->view->content = $this->view->render('management/edit.phtml');        
        \ZF\View\ViewPlugin::setNoRender();
    }    

    public function editAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$news = $em->find('\ZF\Entities\News', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $this->view->form = new \Application_Form_News(null, "edit");
	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $auth = \Zend_Auth::getInstance();
                $user = $em->getRepository('\ZF\Entities\User')->findOneById($auth->getIdentity()->getId());
                
                $news->setTitle($this->_getParam("title"));
                $news->setAnons($this->_getParam("anons"));
                $news->setText($this->_getParam("text"));
                $news->setAuthor($user);
                $news->setDtUpdate(new \DateTime());

                if ( is_null($em->flush()) )
                {
                    //Edit tags
                    \ZF\Controller\Tags::newsEditRelations($news, $this->_getParam("tags"));
                    $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
                }
            }
    		else
    		{
    			$this->view->form->populate($this->_request->getPost());
    		}
        }

        $this->view->form->populate(array('title'=>$news->getTitle(),
                                            'anons'=>$news->getAnons(),
                                            'text'=>$news->getText(),
                                            'tags'=>$em->getRepository('\ZF\Entities\NewsTagRel')->getByNewsToString($news)
                                    ));
        $this->view->title = $this->view->translate('Edit user');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();        
    }

    /**
     * Delete user from list
     * @return void
     */
    public function deleteAction()
    {
        $entity = \Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\News');
        $relation = \Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\NewsTagRel');
        return parent::delete($entity, $relation);
    }    
}