<?php
/**
 * User: raccoon
 * Date: 09.02.12 14:55
 */
namespace ZF\Repositories;
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get user by email or nickname, for Auth
     * @param  $loginOrEmail
     * @return mixed
     */
    public function getUserByLoginOrEmail($loginOrEmail)
    {
        $emailValidator = new \Zend_Validate_EmailAddress();
        if ($emailValidator->isValid($loginOrEmail))
        {
            $query = $this->_em->createQuery("SELECT u
                                                FROM ZF\Entites\User u
                                               WHERE u.email=:value");
        }
        else
        {
            $query = $this->_em->createQuery("SELECT u
                                                FROM ZF\Entites\User u
                                               WHERE u.nickname=:value");
        }
        $query->setParameter("value", $loginOrEmail);
        return $query->getOneOrNullResult();
    }
}
