<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PageTagRel
 *
 * @ORM\Table(name="page_tag_rel")
 * @ORM\Entity(repositoryClass="\ZF\Repositories\PageTagRelRepository")
 */
class PageTagRel
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
     * @var Tags
     *
     * @ORM\OneToOne(targetEntity="Tags")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tag_id", referencedColumnName="id", unique=true)
     * })
     */
    private $tag;

    /**
     * @var Page
     *
     * @ORM\OneToOne(targetEntity="Page")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id", unique=true)
     * })
     */
    private $page;


    function __construct()
    {
        $this->page = new ArrayCollection();
        $this->tag = new ArrayCollection();
    }

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
     * Set tag
     *
     * @param Tags $tag
     * @return PageTagRel
     */
    public function setTag(Tags $tag = null)
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

    /**
     * Set page
     *
     * @param Page $page
     * @return PageTagRel
     */
    public function setPage(Page $page = null)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get page
     *
     * @return Page 
     */
    public function getPage()
    {
        return $this->page;
    }
}