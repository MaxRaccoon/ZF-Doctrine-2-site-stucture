<?php
/**
 * User: raccoon
 * Date: 15.02.12 14:30
 */
namespace ZF\Repositories;
class NewsTagRelRepository extends \Doctrine\ORM\EntityRepository implements \ZF\Interfaces\RelationAction
{
    /**
     * Clear relation by one or two parameters
     * @throws \Exception
     * @param  $entity \ZF\Entities\Tags OR \ZF\Entities\News
     * @param null $second_entity \ZF\Entities\Tags OR \ZF\Entities\News (must differ from the first)
     * @return mixed
     */
    public function clearRelations($entity, $second_entity = null)
    {
        if ($entity instanceof \ZF\Entities\News)
        {
            $field = "news";
            $second_field = "tag";
            if (!is_null($second_entity) && !$second_entity instanceof \ZF\Entities\Tags)
            {
                throw new \Exception("Second param must by instance of Tags (if first is News)!");
            }
        }
        elseif ($entity instanceof \ZF\Entities\Tags)
        {
            $field = "tag";
            $second_field = "news";
            if (!is_null($second_entity) && !$second_entity instanceof \ZF\Entities\News)
            {
                throw new \Exception("Second param must by instance of News (if first is Tags)!");
            }
        }
        else
        {
            throw new \Exception("First param must by instance of News or Tag!");
        }

        $query = $this->_em->createQuery("DELETE FROM ZF\Entities\NewsTagRel rel
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
     * Get tags by news
     * @param \ZF\Entities\News $news
     * @return mixed
     */
    public function getByNews(\ZF\Entities\News $news)
    {
        $query = $this->_em->createQuery("SELECT rel, n, t
                                            FROM ZF\Entities\NewsTagRel rel
                                            JOIN rel.news n
                                            JOIN rel.tag t
                                           WHERE rel.news=:value");
        $query->setParameter("value", $news);
        return $query->execute();
    }

    /**
     * @param \ZF\Entities\News $news
     * @return string
     */
    public function getByNewsToString(\ZF\Entities\News $news)
    {
        $tag_string = "";
        foreach ($this->getByNews($news) AS $rel)
        {
            $tag_string .= $rel->getTag()->getName() . " ";
        }
        return $tag_string;
    }
}