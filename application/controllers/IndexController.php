<?php
class IndexController extends Zend_Controller_Action
{
    protected $em = null;
    
    public function init()
    {
        /* Initialize doctrine */
        $this->em = \Zend_Registry::get('doctrine')->getEntityManager();
    }

    public function indexAction()
    {
        $this->view->title = $this->view->translate('Welcome to Default site on RaccoonZF!');
        $this->view->content = $this->view->render('index/index.phtml');
    }

    /**
     * Action for generate ORM model
     */
    public function setupAction()
    {
        try
        {
            $role = $this->em->getRepository('\ZF\Entity\AclRole')->find(3);
            $user = new \ZF\Entities\User;
            $user->setEmail('admin@default.zf');
            $user->setPassword('admin');
            $user->setAclRole($role);
            $user->setNickname("Raccoon");
            $user->setFirstName("Maxim");
            $user->setLastName("Tereshchenko");
            $this->em->persist($user);
            $this->em->flush();
        }
        catch (Exception $e)
        {
            var_dump($e);
        }
        var_dump($user); exit();
    }
}

