<?php
/**
 * User: raccoon
 * Date: 25.02.12 17:13
 */
class SlidermanagementController extends \ZF\Controller\Managment
{
    private static $_width = 940,
                    $_height = 445;

    /**
     * Show slider list
     * @return void
     */
    public function listAction()
    {
        $entity_name = '\ZF\Entities\Slider';
        $titles = array("id"=>"№", "Title"=>"Title", "#moveup"=>"Move up", "#movedown"=>"Move down", "#edit"=>"Edit", "#delete"=>"Delete");
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
                $file_info = ZF\Plugins\Uploader::upload( "slider" );
                //Prepare images
                $image = new Zend_Image( $file_info['path'], new Zend_Image_Driver_Gd );
                $transformed = new Zend_Image_Transform( $image );
                if ($image->getHeight() <= $image->getWidth())
                {
                    $transformed->fitToWidth( self::$_width )->center()->crop( self::$_width, self::$_height );

                }
                else
                {
                    $transformed->fitToHeight( self::$_height )->middle()->crop( self::$_width, self::$_height );
                }
                $transformed->save( $file_info["path"] );
                chmod( $file_info["path"], 0644);
                //save trumpbnail
                $transformed->fitToWidth( 100 );
                $preview_name = $file_info["dir"] . "preview" . DIRECTORY_SEPARATOR . $file_info["filename"];
                $transformed->save( $preview_name );
                chmod( $preview_name, 0644);

                $em = \Zend_Registry::get('doctrine')->getEntityManager();
                
    			$Slider = new \ZF\Entities\Slider();
                $Slider->setTitle($this->_getParam("title"));
                $Slider->setText($this->_getParam("text"));
                $Slider->setPic($file_info["filename"]);
                $Slider->setPosition( $em->getRepository('\ZF\Entities\Slider')->getLastPosition() + 1 );

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

    /**
     * Edit slider
     * @return void
     */
    public function editAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Slider = $em->find('\ZF\Entities\Slider', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $this->view->form = new \Application_Form_Slider(null, "edit");
	    if($this->_request->isPost())
    	{
    		if( $this->view->form->isValid($this->_request->getPost()) )
    		{
                $upload = new Zend_File_Transfer();
                $file = $upload->getFileInfo();
                if ( $file["pic"]["name"] !== "" )
                {
                    //Load image
                    $file_info = ZF\Plugins\Uploader::upload( "slider" );
                    //Prepare images
                    $image = new Zend_Image( $file_info['path'], new Zend_Image_Driver_Gd );
                    $transformed = new Zend_Image_Transform( $image );
                    if ($image->getHeight() <= $image->getWidth())
                    {
                        $transformed->fitToWidth( self::$_width )->center()->crop( self::$_width, self::$_height );

                    }
                    else
                    {
                        $transformed->fitToHeight( self::$_height )->middle()->crop( self::$_width, self::$_height );
                    }
                    $transformed->save( $file_info["path"] );
                    chmod( $file_info["path"], 0644);
                    //save trumpbnail
                    $transformed->fitToWidth( 100 );
                    $preview_name = $file_info["dir"] . "preview" . DIRECTORY_SEPARATOR . $file_info["filename"];
                    $transformed->save( $preview_name );
                    chmod( $preview_name, 0644);

                    $Slider->setPic($file_info["filename"]);
                }

                $Slider->setTitle($this->_getParam("title"));
                $Slider->setText($this->_getParam("text"));                

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

        $this->view->form->populate(array('title'=>$Slider->getTitle(),
                                            'text'=>$Slider->getText()
                                    ));
        $this->view->title = $this->view->translate('Edit slider');
        $this->view->content = $this->view->render('management/edit.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Delete slider from list
     * @return void
     */
    public function deleteAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Slider = $em->find('\ZF\Entities\Slider', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em->getRepository('\ZF\Entities\Slider')->removeSlide($Slider);
        $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
    }

    /**
     * @return void
     */
    public function moveupAction()
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        if ( !$Slider = $em->find('\ZF\Entities\Slider', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        if (!$moveDownMenu = $em->getRepository('\ZF\Entities\Slider')->getByPosition( ($Slider->getPosition() - 1) ))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $moveDownMenu->setPosition($Slider->getPosition());
        $Slider->setPosition($Slider->getPosition() - 1);
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
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        if ( !$Slider = $em->find('\ZF\Entities\Slider', (int)$ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        if (!$moveUpMenu = $em->getRepository('\ZF\Entities\Slider')->getByPosition( ($Slider->getPosition() + 1) ))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $moveUpMenu->setPosition($Slider->getPosition());
        $Slider->setPosition($Slider->getPosition() + 1);
        $em->flush();
        $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
    }
    
}