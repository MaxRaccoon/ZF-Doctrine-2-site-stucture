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
}