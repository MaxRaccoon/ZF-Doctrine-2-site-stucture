<?php
/**
 * User: raccoon
 * Date: 09.02.12 18:23
 */
namespace ZF\Repositories;
class AclGroupDenyRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param \ZF\Entities\AclRole $role
     * @return mixed
     */
    public function getDenyForRole(\ZF\Entities\AclRole $role)
    {
        $roles = array($role);
        $parent = $role->getParent();
        while (!is_null($parent))
        {
            $roles[] = $parent;
            $parent = $parent->getParent();
        }

        $query = $this->_em->createQuery("SELECT d, ac, aa, r
                                            FROM \ZF\Entities\AclGroupDeny d
                                            LEFT JOIN d.aclController ac
                                            LEFT JOIN d.aclAction aa
                                            LEFT JOIN d.aclRole r
                                           WHERE d.aclRole IN (:roles)");
        $query->setParameter("roles", $roles);
        return $query->execute();
    }
} 
