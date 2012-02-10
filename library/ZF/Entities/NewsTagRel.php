<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * NewsTagRel
 *
 * @ORM\Table(name="news_tag_rel")
 * @ORM\Entity
 */
class NewsTagRel
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var News
     *
     * @ORM\OneToOne(targetEntity="News")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_id", referencedColumnName="id", unique=true)
     * })
     */
    private $news;

    /**
     * @var Tags
     *
     * @ORM\OneToOne(targetEntity="Tags")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tag_id", referencedColumnName="id", unique=true)
     * })
     */
    private $tag;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set news
     *
     * @param News $news
     * @return NewsTagRel
     */
    public function setNews(\News $news = null)
    {
        $this->news = $news;
        return $this;
    }

    /**
     * Get news
     *
     * @return News 
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set tag
     *
     * @param Tags $tag
     * @return NewsTagRel
     */
    public function setTag(\Tags $tag = null)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Get tag
     *
     * @return Tags 
     */
    public function getTag()
    {
        return $this->tag;
    }
}