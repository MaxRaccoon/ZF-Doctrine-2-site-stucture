<?php
/**
 * User: raccoon
 * Date: 20.02.12 18:44
 */
class CustomermanagementController extends \ZF\Controller\Managment
{
    /**
     * Show customer list
     * @return void
     */
    public function listAction()
    {
        $entity_name = '\ZF\Entities\Customer';
        $titles = array("id"=>"№", "Title"=>"Title", "Url"=>"Url", "Phone"=>"Phone", "Email"=>"Email", "#edit"=>"Edit", "#delete"=>"Delete");
        $title = "Customer list";
        parent::showList($entity_name, $titles, $title);
    }

    /**
     * Add customer
     * @return void
     */
    public function addAction()
    {
        $this->view->form = new \Application_Form_Customer(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $em = \Zend_Registry::get('doctrine')->getEntityManager();
                
    			$Customer = new \ZF\Entities\Customer();
                $Customer->setTitle($this->_getParam("title"));
                $Customer->setUrl($this->_getParam("url"));
                $Customer->setEmail($this->_getParam("email"));
                $Customer->setPhone($this->_getParam("phone"));
                $Customer->setDescription($this->_getParam("description"));

                $em->persist($Customer);
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

        $this->view->title = $this->view->translate('Add customer');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Edit customers
     * @return void
     */
    public function editAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Customer = $em->find('\ZF\Entities\Customer', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $this->view->form = new \Application_Form_Customer(null, "edit");
	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $Customer->setTitle($this->_getParam("title"));
                $Customer->setUrl($this->_getParam("url"));
                $Customer->setEmail($this->_getParam("email"));
                $Customer->setPhone($this->_getParam("phone"));
                $Customer->setDescription($this->_getParam("description"));

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

        $this->view->form->populate(array('title'=>$Customer->getTitle(),
                                            'url'=>$Customer->getUrl(),
                                            'email'=>$Customer->getEmail(),
                                            'phone'=>$Customer->getPhone(),
                                            'description'=>$Customer->getDescription()
                                    ));
        $this->view->title = $this->view->translate('Edit customer');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Delete customer from list
     * @return void
     */
    public function deleteAction()
    {
        $entity = \Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\Customer');
        return parent::delete($entity);
    }      
}