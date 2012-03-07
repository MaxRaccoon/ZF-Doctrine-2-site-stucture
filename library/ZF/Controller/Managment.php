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
        elseif ( $entity_name == '\ZF\Entities\Menu' || $entity_name == '\ZF\Entities\Slider' )
        {
            $list = $em->getRepository($entity_name)->getAll();
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
            return \ZF\Error\Page::pageNotFound($this->getRequest());
        }

        if ( !$object = $entity->findOneById($ID) )
        {
            return \ZF\Error\Page::pageNotFound($this->getRequest());
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