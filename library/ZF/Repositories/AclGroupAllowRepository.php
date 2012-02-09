<?php
/**
 * User: raccoon
 * Date: 09.02.12 17:52
 */
namespace ZF\Repositories;
class AclGroupAllowRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param \ZF\Entites\AclRole $role
     * @return mixed
     */
    public function getAllowForRole(\ZF\Entites\AclRole $role)
    {
        $roles = array($role);
        $parent = $role->getParent();
        while (!is_null($parent))
        {
            $roles[] = $parent;
            $parent = $parent->getParent();
        }
        
        $query = $this->_em->createQuery("SELECT a, ac, aa, r
                                            FROM \ZF\Entites\AclGroupAllow a
                                            LEFT JOIN a.aclController ac
                                            LEFT JOIN a.aclAction aa
                                            LEFT JOIN a.aclRole r
                                           WHERE a.aclRole IN (:roles)");
        $query->setParameter("roles", $roles);
        return $query->execute();
    }
}
