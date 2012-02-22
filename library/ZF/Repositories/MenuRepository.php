<?php
/**
 * User: raccoon
 * Date: 23.02.12 0:10
 */
namespace ZF\Repositories;
class MenuRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        $query = $this->_em->createQuery("SELECT m, ac, aa
                                            FROM ZF\Entities\Menu m
                                            JOIN m.aclController ac
                                            JOIN m.aclAction aa
                                            ORDER BY m.position");
        return $query->execute();
    }
}
