<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity
 */
class Page
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
     * @var datetime $dtCreate
     *
     * @ORM\Column(name="dt_create", type="datetime", nullable=false)
     */
    private $dtCreate;

    /**
     * @var datetime $dtUpdate
     *
     * @ORM\Column(name="dt_update", type="datetime", nullable=false)
     */
    private $dtUpdate;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=250, nullable=false)
     */
    private $title;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=250, nullable=false)
     */
    private $url;

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var string $metaTags
     *
     * @ORM\Column(name="meta_tags", type="string", length=250, nullable=false)
     */
    private $metaTags;

    /**
     * @var boolean $isDeleted
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     */
    private $isDeleted;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="author_id", referencedColumnName="id", unique=true)
     * })
     */
    private $author;

    
    function __construct()
    {
        $this->setIsDeleted(0);
        $this->author = new ArrayCollection();
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
     * Set dtCreate
     *
     * @param datetime $dtCreate
     * @return Page
     */
    public function setDtCreate($dtCreate)
    {
        $this->dtCreate = $dtCreate;
        return $this;
    }

    /**
     * Get dtCreate
     *
     * @return datetime 
     */
    public function getDtCreate()
    {
        return $this->dtCreate;
    }

    /**
     * Set dtUpdate
     *
     * @param datetime $dtUpdate
     * @return Page
     */
    public function setDtUpdate($dtUpdate)
    {
        $this->dtUpdate = $dtUpdate;
        return $this;
    }

    /**
     * Get dtUpdate
     *
     * @return datetime 
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set text
     *
     * @param text $text
     * @return Page
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set metaTags
     *
     * @param string $metaTags
     * @return Page
     */
    public function setMetaTags($metaTags)
    {
        $this->metaTags = $metaTags;
        return $this;
    }

    /**
     * Get metaTags
     *
     * @return string 
     */
    public function getMetaTags()
    {
        return $this->metaTags;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Page
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set author
     *
     * @param User $author
     * @return Page
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}