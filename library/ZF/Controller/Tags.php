<?php
/**
 * User: raccoon
 * Date: 15.02.12 13:33
 */
namespace ZF\Controller;
class Tags
{
    /**
     * Add new News-Tags relations
     * @param \ZF\Entities\News $news
     * @param string $tags_string
     * @return bool
     */
    public static function newsAddRelations(\ZF\Entities\News $news, $tags_string = "")
    {
        if (trim($tags_string) == "")
        {
            return true;
        }

        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        foreach (explode(" ",$tags_string) AS $tag_name)
        {
            $new_rel = new \ZF\Entities\NewsTagRel();
            $new_rel->setNews($news);

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
     * @param \ZF\Entities\News $news
     * @param string $tags_string
     * @return bool
     */
    public static function newsEditRelations(\ZF\Entities\News $news, $tags_string = "")
    {
        $em = \Zend_Registry::get('doctrine')->getEntityManager();
        $old_string = $em->getRepository('\ZF\Entities\NewsTagRel')->getByNewsToString($news);
        if (trim($tags_string) == trim($old_string))
        {
            return true;
        }

        //Clear old tags
        if (trim($tags_string) == "")
        {
           $em->getRepository('\ZF\Entities\NewsTagRel')->clearRelations($news);
           return true;
        }

        $isset_tags = array();
        if ($relations = $em->getRepository('\ZF\Entities\NewsTagRel')->getByNews($news))
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
                $new_rel = new \ZF\Entities\NewsTagRel();
                $new_rel->setNews($news);
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
                $em->getRepository('\ZF\Entities\NewsTagRel')->clearRelations($news, $deleted_tag);
            }
        }

        return true;        
    }
}