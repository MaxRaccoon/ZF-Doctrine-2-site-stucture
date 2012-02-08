<?php
namespace ZF\Entites;
use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity
 */
class News
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
     * @var text $anons
     *
     * @ORM\Column(name="anons", type="text", nullable=false)
     */
    private $anons;

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

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
     * @return News
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
     * @return News
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
     * @return News
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
     * Set anons
     *
     * @param text $anons
     * @return News
     */
    public function setAnons($anons)
    {
        $this->anons = $anons;
        return $this;
    }

    /**
     * Get anons
     *
     * @return text 
     */
    public function getAnons()
    {
        return $this->anons;
    }

    /**
     * Set text
     *
     * @param text $text
     * @return News
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
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return News
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
     * @return News
     */
    public function setAuthor(\User $author = null)
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