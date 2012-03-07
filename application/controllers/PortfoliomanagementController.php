<?php
/**
 * User: raccoon
 * Date: 20.02.12 23:01
 */
class PortfoliomanagementController extends \ZF\Controller\Managment
{
    /**
     * Show customer list
     * @return void
     */
    public function listAction()
    {
        $entity_name = '\ZF\Entities\Portfolio';
        $titles = array("id"=>"№", "Title"=>"Title", "Url"=>"Url", "#edit"=>"Edit", "#delete"=>"Delete");
        $title = "Portfolio list";
        parent::showList($entity_name, $titles, $title);
    }

    /**
     * Add portfolio item
     * @return void
     */
    public function addAction()
    {
        $this->view->form = new \Application_Form_Portfolio(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $em = \Zend_Registry::get('doctrine')->getEntityManager();
                $customer = $em->getRepository('\ZF\Entities\Customer')->findOneById((int)$this->_getParam("customer"));

    			$Portfolio = new \ZF\Entities\Portfolio();
                $Portfolio->setTitle($this->_getParam("title"));
                $Portfolio->setUrl($this->_getParam("url"));
                $Portfolio->setDescription($this->_getParam("description"));
                $Portfolio->setDtCreate(new \DateTime());
                if ($this->_getParam("dt_launch") != "")
                {
                    $Portfolio->setDtLaunch( new \DateTime($this->_getParam("dt_launch")) );
                }
                $Portfolio->setCustomer($customer);

                $em->persist($Portfolio);
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
     * Edit portfolio item
     * @return void
     */
    public function editAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Portfolio = $em->find('\ZF\Entities\Portfolio', (int)$ID) )
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $this->view->form = new \Application_Form_Portfolio(null, "edit");
	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $customer = $em->getRepository('\ZF\Entities\Customer')->findOneById((int)$this->_getParam("customer"));
                
                $Portfolio->setTitle($this->_getParam("title"));
                $Portfolio->setUrl($this->_getParam("url"));
                $Portfolio->setDescription($this->_getParam("description"));
                $Portfolio->setDtCreate(new \DateTime());
                if ($this->_getParam("dt_launch") != "")
                {
                    $Portfolio->setDtLaunch( new \DateTime($this->_getParam("dt_launch")) );
                }
                else
                {
                    $Portfolio->setDtLaunch(null);
                }
                $Portfolio->setCustomer($customer);

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

        $this->view->form->populate(array('title'=>$Portfolio->getTitle(),
                                            'url'=>$Portfolio->getUrl(),
                                            'dt_launch'=>$Portfolio->getDtLaunch()->format("Y-m-d"),
                                            'description'=>$Portfolio->getDescription(),
                                            'customer'=>$Portfolio->getCustomer()->getId(),
                                            'pic_dir'=>$Portfolio->getId()
                                    ));
        $this->view->headScript()->prependFile('/js/jquery-plugins/ajaxupload.js');
        $this->view->headScript()->prependFile('/js/upload.js');
        $this->view->title = $this->view->translate('Edit customer');
        $this->view->content = $this->view->render('portfolio/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Delete portfolio from list
     * @return void
     */
    public function deleteAction()
    {
        $entity = \Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\Portfolio');
        return parent::delete($entity);
    }       
}
