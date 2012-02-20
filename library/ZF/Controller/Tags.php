<?php
/**
 * User: raccoon
 * Date: 15.02.12 13:33
 */
namespace ZF\Controller;
class Tags
{
    /**
     * Add new News|Page-Tags relations
     * @param \ZF\Entities\News OR \ZF\Entities\Page $entity
     * @param string $tags_string
     * @return bool
     */
    public static function addRelations($entity, $tags_string = "")
    {
        if ( !$entity instanceof \ZF\Entities\News && !$entity instanceof \ZF\Entities\Page )
        {
            throw new \Exception("Entity must be instance of \ZF\Entities\News or \ZF\Entities\Page!");
        }

        if (trim($tags_string) == "")
        {
            return true;
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        foreach (explode(" ",$tags_string) AS $tag_name)
        {
            if ($entity instanceof \ZF\Entities\News)
            {
                $new_rel = new \ZF\Entities\NewsTagRel();
                $new_rel->setNews($entity);
            }
            elseif ($entity instanceof \ZF\Entities\Page)
            {
                $new_rel = new \ZF\Entities\PageTagRel();
                $new_rel->setPage($entity);
            }

            //If tag not isset, yet - add new tag in DB 
            if (!$tag = $em->getRepository('\ZF\Entities\Tags')->findOneByName($tag_name))
            {
                $tag = new \ZF\Entities\Tags();
                $tag->setName($tag_name);
                $em->persist($tag);
                $em->flush();
            }

            //Add tag rel
            $new_rel->setTag($tag);
            $em->persist($new_rel);
            $em->flush();
        }
        
        return true;
    }

    /**
     * @static
     * @param \ZF\Entities\News OR \ZF\Entities\Page $entity
     * @param string $tags_string
     * @return bool
     */
    public static function editRelations($entity, $tags_string = "")
    {
        if ( $entity instanceof \ZF\Entities\News )
        {
            $rel_name = "\ZF\Entities\NewsTagRel";
        }
        elseif ( $entity instanceof \ZF\Entities\Page )
        {
            $rel_name = "\ZF\Entities\PageTagRel";
        }
        else
        {
            throw new \Exception("Entity must be instance of \ZF\Entities\News or \ZF\Entities\Page!");
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $old_string = $em->getRepository($rel_name)->getTagsInString($entity);
        if (trim($tags_string) == trim($old_string))
        {
            return true;
        }

        //Clear old tags
        if (trim($tags_string) == "")
        {
           $em->getRepository($rel_name)->clearRelations($entity);
           return true;
        }

        $isset_tags = array();
        if ($relations = $em->getRepository($rel_name)->getTags($entity))
        {
            foreach ($relations AS $item)
            {
                $isset_tags[] = $item->getTag();
            }
        }

        foreach (explode(" ",$tags_string) AS $tag_name)
        {
            //If tag not isset, yet - add new tag in DB
            if (!$tag = $em->getRepository('\ZF\Entities\Tags')->findOneByName($tag_name))
            {
                $tag = new \ZF\Entities\Tags();
                $tag->setName($tag_name);
                $em->persist($tag);
                $em->flush();
            }

            //Search new tag in old tags array
            if (array_search($tag, $isset_tags) === false)
            {
                //Add tag rel
                if ( $entity instanceof \ZF\Entities\News )
                {
                    $new_rel = new \ZF\Entities\NewsTagRel();
                    $new_rel->setNews($entity);
                }
                elseif ( $entity instanceof \ZF\Entities\Page )
                {
                    $new_rel = new \ZF\Entities\PageTagRel();
                    $new_rel->setPage($entity);
                }
                $new_rel->setTag($tag);
                $em->persist($new_rel);
                $em->flush();
            }
            else
            {
                //If new tag isset in old array, delete it from this array
                unset($isset_tags[array_search($tag, $isset_tags)]);
            }
        }

        //Delete old tags
        if (!empty($isset_tags))
        {
            foreach ($isset_tags AS $deleted_tag)
            {
                $em->getRepository($rel_name)->clearRelations($entity, $deleted_tag);
            }
        }

        return true;        
    }
}