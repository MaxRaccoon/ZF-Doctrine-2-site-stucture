<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
    
/**
 * Portfolio
 *
 * @ORM\Table(name="portfolio")
 * @ORM\Entity
 */
class Portfolio
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
     * @var datetime $dtLaunch
     *
     * @ORM\Column(name="dt_launch", type="datetime", nullable=true)
     */
    private $dtLaunch;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=250, nullable=false)
     */
    private $title;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=250, nullable=true)
     */
    private $url;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var boolean $isDeleted
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     */
    private $isDeleted;

    /**
     * @var Customer
     *
     * @ORM\OneToOne(targetEntity="Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id", unique=true)
     * })
     */
    private $customer;


    function __construct()
    {
        $this->customer = new ArrayCollection();
        $this->setIsDeleted(0);
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
     * @return Portfolio
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
     * Set dtLaunch
     *
     * @param datetime $dtLaunch
     * @return Portfolio
     */
    public function setDtLaunch($dtLaunch)
    {
        $this->dtLaunch = $dtLaunch;
        return $this;
    }

    /**
     * Get dtLaunch
     *
     * @return datetime 
     */
    public function getDtLaunch()
    {
        return $this->dtLaunch;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Portfolio
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
     * @return Portfolio
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
     * Set description
     *
     * @param text $description
     * @return Portfolio
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
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Portfolio
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
     * Set customer
     *
     * @param Customer $customer
     * @return Portfolio
     */
    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get customer
     *
     * @return Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}