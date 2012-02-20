<?php
/**
 * User: raccoon
 * Date: 15.02.12 12:42
 */
namespace ZF\Controller;
class Managment extends \Zend_Controller_Action
{
    public function showList($entity_name, $titles, $title, $hasDelete = true)
    {
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        if ($hasDelete)
        {
            $list = $em->getRepository($entity_name)->findByIsDeleted(false);
        }
        else
        {
            $list = $em->getRepository($entity_name)->findAll();
        }

        $this->view->list = new \ZF\View\ListView($this->getRequest()->getControllerName(),
                                                    $titles,
                                                    $list);
        $this->view->list->setTitle($title);

        $this->view->content = $this->view->render('management/list.phtml');
        \ZF\View\ViewPlugin::setNoRender();
    }

    /**
     * Delete entity from list by id
     * @return void
     */
    public function delete($entity, $relation = null)
    {
        if (!$ID = $this->getRequest()->getParam('ID', false))
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        if ( !$object = $entity->findOneById($ID) )
        {
            $this->_redirect($this->view->url(array('controller'=>'error','action'=>'notfound'), 'page_not_found'));
        }

        //Clear relations
        if (!is_null($relation))
        {
            //$relation->clear($object);
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $object->setIsDeleted(true);
        $em->flush();
        $this->_redirect($this->view->url(array('controller'=>$this->getRequest()->getControllerName(),'action'=>'list'), 'default'));
    }
}