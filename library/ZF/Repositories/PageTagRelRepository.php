<?php
/**
 * User: raccoon
 * Date: 17.02.12 16:53
 */
namespace ZF\Repositories;
class PageTagRelRepository extends \Doctrine\ORM\EntityRepository implements \ZF\Interfaces\RelationAction
{
    /**
     * Clear relation by one or two parameters
     * @throws \Exception
     * @param  $entity \ZF\Entities\Tags OR \ZF\Entities\Page
     * @param null $second_entity \ZF\Entities\Tags OR \ZF\Entities\News (must differ from the first)
     * @return mixed
     */
    public function clearRelations($entity, $second_entity = null)
    {
        if ($entity instanceof \ZF\Entities\Page)
        {
            $field = "page";
            $second_field = "tag";
            if (!is_null($second_entity) && !$second_entity instanceof \ZF\Entities\Tags)
            {
                throw new \Exception("Second param must by instance of Tags (if first is News)!");
            }
        }
        elseif ($entity instanceof \ZF\Entities\Tags)
        {
            $field = "tag";
            $second_field = "page";
            if (!is_null($second_entity) && !$second_entity instanceof \ZF\Entities\Page)
            {
                throw new \Exception("Second param must by instance of Page (if first is Tags)!");
            }
        }
        else
        {
            throw new \Exception("First param must by instance of Page or Tag!");
        }

        $query = $this->_em->createQuery("DELETE FROM ZF\Entities\PageTagRel rel
                                           WHERE rel." . $field . "=:value"
                                            . ( is_null($second_entity) ? "" : " AND rel." . $second_field . "=:second" ) );
        if (is_null($second_entity))
        {
            $query->setParameter("value", $entity);
        }
        else
        {
            $query->setParameters(array("value"=>$entity, "second"=>$second_entity));
        }
        return $query->execute();
    }

    /**
     * Get tags by page
     * @param \ZF\Entities\Page $entity
     * @return mixed
     */
    public function getTags($entity)
    {
        $query = $this->_em->createQuery("SELECT rel, p, t
                                            FROM ZF\Entities\PageTagRel rel
                                            JOIN rel.page p
                                            JOIN rel.tag t
                                           WHERE rel.page=:value");
        $query->setParameter("value", $entity);
        return $query->execute();
    }

    /**
     * @param \ZF\Entities\Page $entity
     * @return string
     */
    public function getTagsInString($entity)
    {
        $tag_string = "";
        foreach ($this->getTags($entity) AS $rel)
        {
            $tag_string .= $rel->getTag()->getName() . " ";
        }
        return $tag_string;
    }
}