<?php
/**
 * User: raccoon
 * Date: 25.02.12 17:13
 */
class SlidermanagementController extends \ZF\Controller\Managment
{
    /**
     * Show slider list
     * @return void
     */
    public function listAction()
    {
        $entity_name = '\ZF\Entities\Slider';
        $titles = array("id"=>"№", "Title"=>"Title", "#edit"=>"Edit", "#delete"=>"Delete");
        $title = "Slider list";
        parent::showList($entity_name, $titles, $title, false);
    }

    /**
     * Add slide
     * @return void
     */
    public function addAction()
    {
        $this->view->form = new \Application_Form_Slider(null, "add");

	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                //Load image
                $file_info = ZF\Plugins\Uploader::upload( "tmp" );
                //Prepare images
                $image = \Zend_Image( $file_info['path'], new \Zend_Image_Driver_Imagick );
                $transformed = \Zend_Image_Transform( $image );
                $transformed->fitToWidth( 940 )->center()->middle();

                $em = \Zend_Registry::get('doctrine')->getEntityManager();
                
    			$Slider = new \ZF\Entities\Slider();
                $Slider->setTitle($this->_getParam("title"));
                $Slider->setText($this->_getParam("text"));

                $em->persist($Slider);
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

        $this->view->headScript()->prependFile('/js/generate_url.js');
        $this->view->title = $this->view->translate('Add slide');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }    
}