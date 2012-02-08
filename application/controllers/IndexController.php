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
        // action body
    }

    /**
     * Action for generate ORM model
     */
    public function setupAction()
    {
        try
        {
            $role = $this->em->getRepository('\ZF\Entity\AclRole')->find(3);
            $user = new \ZF\Entity\User;
            $user->setEmail('enot.work@gmail.com');
            $user->setPassword('egT4dO');
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

