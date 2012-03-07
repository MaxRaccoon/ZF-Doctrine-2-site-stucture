<?php
/**
 * User: raccoon
 * Date: 17.02.12 13:05
 */
class AjaxController extends Zend_Controller_Action
{
    /**
     * If is ajax request - turn off view renderer
     * else return 404
     * @return void
     */
    public function init()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            Zend_Controller_Action_HelperBroker::resetHelpers();
        }
    }

    /**
     * @return void
     */
    public function generateurlAction()
    {
        if( $this->_request->isPost() && $from = $this->_getParam("from") )
        {
            $url = ZF\Plugins\Transliter::generateUrl($from);
            $result = array("RESULT"=>1, "url"=>$url);
        }
        else
        {
            $result = array("RESULT"=>0);
        }
        $output = Zend_Json::encode($result);
        $response = $this->getResponse();
        $response->setBody($output)
            ->setHeader('content-type', 'application/json', true);
    }

    /**
     * @return void
     */
    public function uploadAction()
    {
        if (!$for = $this->getRequest()->getParam('for', false))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        if (!$dir = $this->getRequest()->getParam('dir', false))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        Zend_Controller_Action_HelperBroker::resetHelpers();

        if ($file_info = ZF\Plugins\Uploader::upload( $for . DIRECTORY_SEPARATOR . $dir ))
        {
            $result = array("RESULT"=>1, "file"=>$file_info['url']);
        }
        else
        {
            $result = array("RESULT"=>0);
        }

        $output = Zend_Json::encode($result);
        $response = $this->getResponse();
        $response->setBody($output)
            ->setHeader('content-type', 'application/json', true);        
    }
}