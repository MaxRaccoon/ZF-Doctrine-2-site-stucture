<?php
/**
 * User: raccoon
 * Date: 28.02.12 9:58
 */
class PageController extends \ZF\Controller\Managment
{
    public function openAction()
    {
        if (!$page_url = $this->getRequest()->getParam('page', false))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        if (!$content = $em->getRepository('\ZF\Entities\Page')->findOneByUrl($page_url))
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        $this->view->title = $content->getTitle();
        $this->view->text = $content->getText();
        $this->view->content = $this->view->render('page/open.phtml');
    }
}