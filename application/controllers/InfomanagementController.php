<?php
/**
 * User: raccoon
 * Date: 20.02.12 16:24
 */
class InfomanagementController extends \ZF\Controller\Managment
{
    /**
     * Show list
     * @return void
     */
    public function listAction()
    {
        $entity_name = '\ZF\Entities\Info';
        $titles = array("id"=>"№", "InfoKey"=>"Key", "InfoValue"=>"Value", "#edit"=>"Edit");
        $title = "Page list";
        parent::showList($entity_name, $titles, $title, false);
    }

    /**
     * Add info
     * @return void
     */
    public function addAction()
    {
        $this->view->form = new \Application_Form_Info(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $em = \Zend_Registry::get('doctrine')->getEntityManager();

    			$Info = new \ZF\Entities\Info();
                $Info->setInfoKey($this->_getParam("info_key"));
                $Info->setInfoValue($this->_getParam("info_value"));

                $em->persist($Info);
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

        $this->view->title = $this->view->translate('Add info');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Edit infp
     * @return void
     */
    public function editAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Info = $em->find('\ZF\Entities\Info', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $this->view->form = new \Application_Form_Info(null, "edit");
	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $Info->setInfoKey($this->_getParam("info_key"));
                $Info->setInfoValue($this->_getParam("info_value"));

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

        $this->view->form->populate(array('info_key'=>$Info->getInfoKey(),
                                            'info_value'=>$Info->getInfoValue()
                                    ));
        $this->view->title = $this->view->translate('Edit info');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }    

}