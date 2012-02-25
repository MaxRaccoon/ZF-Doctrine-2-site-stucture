<?php
/**
 * User: raccoon
 * Date: 25.02.12 23:01
 */
namespace ZF\Repositories;
class SliderRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        $query = $this->_em->createQuery("SELECT s
                                            FROM ZF\Entities\Slider s
                                            ORDER BY s.position");
        return $query->execute();
    }

    /**
     * @return int
     */
    public function getLastPosition()
    {
        $query = $this->_em->createQuery("SELECT s.position
                                            FROM ZF\Entities\Slider s
                                            ORDER BY s.position DESC");
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
     * @param int $position
     * @return mixed
     */
    public function getByPosition($position)
    {
        $query = $this->_em->createQuery("SELECT s
                                            FROM ZF\Entities\Slider s
                                            WHERE s.position=:position");
        $query->setParameter("position", (int)$position);
        return $query->getOneOrNullResult();
    }

    /**
     * Delete slide and update all slider position
     * @param \ZF\Entities\Slider $Slider
     * @return mixed
     */
    public function removeSlide(\ZF\Entities\Slider $Slider)
    {
        $delete_position = $Slider->getPosition();
        $query = $this->_em->createQuery("DELETE FROM ZF\Entities\Slider s
                                            WHERE s.id=:id");
        $query->setParameter("id", $Slider->getId());
        $query->execute();

        $query = $this->_em->createQuery("UPDATE ZF\Entities\Slider s
                                            SET s.position=s.position-1
                                            WHERE s.position>:del_pos");
        $query->setParameter("del_pos", $delete_position);
        return $query->execute();
    }
}
