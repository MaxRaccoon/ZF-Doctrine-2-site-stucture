<?php
/**
 * User: raccoon
 * Date: 15.02.12 12:42
 */
namespace ZF\Controller;
class Managment extends \Zend_Controller_Action
{
    /**
     * Delete entity from list by id
     * @return void
     */
    public function delete($entity)
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        if ( !$user = $entity->findOneById($ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $em->remove($user);
        $em->flush();
        $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
    }
}