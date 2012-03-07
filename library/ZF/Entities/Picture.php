<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Picture
 *
 * @ORM\Table(name="picture")
 * @ORM\Entity
 */
class Picture
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
     * @var string $filename
     *
     * @ORM\Column(name="filename", type="string", length=250, nullable=false)
     */
    private $filename;

    /**
     * @var datetime $dtAdd
     *
     * @ORM\Column(name="dt_add", type="datetime", nullable=false)
     */
    private $dtAdd;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=true)
     */
    private $name;

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
        $this->author = new ArrayCollection();
        $this->isDeleted(false);
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
     * Set filename
     *
     * @param string $filename
     * @return Picture
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set dtAdd
     *
     * @param datetime $dtAdd
     * @return Picture
     */
    public function setDtAdd($dtAdd)
    {
        $this->dtAdd = $dtAdd;
        return $this;
    }

    /**
     * Get dtAdd
     *
     * @return datetime 
     */
    public function getDtAdd()
    {
        return $this->dtAdd;
    }

    /**
     * Set description
     *
     * @param text $description
     * @return Picture
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Picture
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Picture
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
     * @return Picture
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