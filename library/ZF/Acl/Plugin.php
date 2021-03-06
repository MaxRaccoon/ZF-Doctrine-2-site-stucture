<?php
/**
 * User: raccoon
 * Date: 08.02.12 22:24
 */
namespace ZF\Acl;
class Plugin extends \Zend_Controller_Plugin_Abstract
{
    /**
     * preDispatch
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $controller = $em->getRepository('\ZF\Entities\AclController')->findOneByName( $request->getControllerName() );
        $action = $em->getRepository('\ZF\Entities\AclAction')->findOneByName( $request->getActionName() );

        //Get role
        $auth = \Zend_Auth::getInstance();
        if ($auth->hasIdentity())
        {
            $user = $auth->getIdentity();
            $role = $user->getAclRole();
        }
        else
        {
            $role = \Zend_Registry::get('doctrine')->getEntityManager()->find('\ZF\Entities\AclRole', 1);
        }

        //Get Acl
        $acl = \Zend_Registry::get('Zend_Acl');
        //Add resources for current role
        $acl = $this->addAclResources( $acl, $role );

        \Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        \Zend_Registry::set('Zend_Acl', $acl);

        //Resource not found
        if (!$acl->has($controller))
        {
            return \ZF\Error\Page::pageNotFound($request);
        }

        //Cheek allow, and redirect if access deny
        if (!$acl->isAllowed($role, $controller, $action->getName() ))
        {
            return \ZF\Error\Page::accessDeny($request);
        }
        else
        {
            return true;
        }
    }

    /**
     * Create Acl relation for current role
     * @return mixed
     */
    public function addAclResources(\Zend_Acl $acl, \ZF\Entities\AclRole $role)
    {
        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        //Group allow
        foreach ( $em->getRepository('\ZF\Entities\AclGroupAllow')->getAllowForRole($role) AS $item )
        {
            if (!$acl->has($item->getAclController()))
            {
                $acl->addResource( $item->getAclController() );
            }
            $acl->allow( $role, $item->getAclController(), ( $item->getAclAction() ? $item->getAclAction()->getName() : null ) );
        }
        //Group deny
        foreach ( $em->getRepository('\ZF\Entities\AclGroupDeny')->getDenyForRole($role) AS $item )
        {
            if (!$acl->has($item->getAclController()))
            {
                $acl->addResource( $item->getAclController() );
            }
            $acl->deny( $role, $item->getAclController(), ( $item->getAclAction() ? $item->getAclAction()->getName() : null ) );
        }

        return $acl;
    }
}
