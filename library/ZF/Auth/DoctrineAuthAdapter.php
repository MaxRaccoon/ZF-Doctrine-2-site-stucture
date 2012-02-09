<?php
/**
 * User: raccoon
 * Date: 09.02.12 14:44
 */
namespace ZF\Auth;
class DoctrineAuthAdapter implements \Zend_Auth_Adapter_Interface
{
    private $_username,
            $_password;

	public function __construct($username, $password)
	{
		$this->_username = $username;
		$this->_password = $password;
	}

	public function authenticate()
	{
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        
		$user = new \ZF\Entites\User();
        $user->setAclRole( $em->find('\ZF\Entites\AclRole', 1) );

        if ($db_user = $em->getRepository('\ZF\Entites\User')->getUserByLoginOrEmail($this->_username))
        {
            $user->setPassword($this->_password);
            if ($user->getPassword() == $db_user->getPassword())
            {
                return new \Zend_Auth_Result(\Zend_Auth_Result::SUCCESS, $db_user);
            }
            else
            {
                return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $user, array("Please, check your login/email and password. And try again."));
            }
        }
        else
        {
            return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $user, array("Please, check your login/email and password. And try again."));
        }
	}
}

