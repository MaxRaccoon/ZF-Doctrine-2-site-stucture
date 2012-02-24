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

    /**
     * @return int
     */
    public function getLastPosition()
    {
        $query = $this->_em->createQuery("SELECT m.position
                                            FROM ZF\Entities\Menu m
                                            ORDER BY m.position DESC");
        $query->setMaxResults(1);
        if (!$res = $query->getOneOrNullResult())
        {
            return 0;
        }
        else
        {
            return $res["position"];
        }
    }

    /**
     * Delete menu and update all menus position
     * @param \ZF\Entities\Menu $Menu
     * @return mixed
     */
    public function removeMenu(\ZF\Entities\Menu $Menu)
    {
        $delete_position = $Menu->getPosition();
        $query = $this->_em->createQuery("DELETE FROM ZF\Entities\Menu m
                                            WHERE m.id=:id");
        $query->setParameter("id", $Menu->getId());
        $query->execute();

        $query = $this->_em->createQuery("UPDATE ZF\Entities\Menu m
                                            SET m.position=m.position-1
                                            WHERE m.position>:del_pos");
        $query->setParameter("del_pos", $delete_position);
        return $query->execute();
    }

    /**
     * @param int $position
     * @return mixed
     */
    public function getByPosition($position)
    {
        $query = $this->_em->createQuery("SELECT m
                                            FROM ZF\Entities\Menu m
                                            WHERE m.position=:position");
        $query->setParameter("position", (int)$position);
        return $query->getOneOrNullResult();
    }
}
