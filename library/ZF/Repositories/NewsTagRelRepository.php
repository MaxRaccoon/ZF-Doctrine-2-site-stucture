<?php
/**
 * User: raccoon
 * Date: 15.02.12 14:30
 */
namespace ZF\Repositories;
class NewsTagRelRepository extends \Doctrine\ORM\EntityRepository implements \ZF\Interfaces\RelationAction
{
    /**
     * Clear relation
     * @throws \Exception
     * @param  $entity
     * @return mixed
     */
    public function clear($entity)
    {
        if ($entity instanceof \ZF\Entities\News)
        {
            $field = "news";
        }
        elseif ($entity instanceof \ZF\Entities\Tag)
        {
            $field = "tag";
        }
        else
        {
            throw new \Exception("First param must by instance of News or Tag!");
        }

        $query = $this->_em->createQuery("DELETE FROM ZF\Entities\NewsTagRel rel
                                           WHERE rel." . $field . "=:value");
        $query->setParameter("value", $entity);
        return $query->execute();
    }
}