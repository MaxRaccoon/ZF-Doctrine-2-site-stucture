<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="\ZF\Repositories\MenuRepository")
 */
class Menu
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
     * @var string $method
     *
     * @ORM\Column(name="method", type="string", length=200, nullable=true)
     */
    private $method;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=250, nullable=false)
     */
    private $title;

    /**
     * @var string $route
     *
     * @ORM\Column(name="route", type="string", length=150, nullable=false)
     */
    private $route;

    /**
     * @var int $position
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;

    /**
     * @var Menu
     *
     * @ORM\OneToOne(targetEntity="Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent", referencedColumnName="id", unique=true)
     * })
     */
    private $parent;

    /**
     * @var AclController
     *
     * @ORM\OneToOne(targetEntity="AclController")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="acl_controller_id", referencedColumnName="id", unique=true)
     * })
     */
    private $aclController;

    /**
     * @var AclAction
     *
     * @ORM\OneToOne(targetEntity="AclAction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="acl_action_id", referencedColumnName="id", unique=true)
     * })
     */
    private $aclAction;

    function __construct()
    {
        $this->aclAction = new ArrayCollection();
        $this->aclController = new ArrayCollection();
        $this->parent = null;
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
     * Set method
     *
     * @param string $method
     * @return Menu
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Get method
     *
     * @return string 
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Menu
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
     * Set position
     *
     * @param int $position
     * @return Menu
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }    

    /**
     * Set route
     *
     * @param string $route
     * @return Menu
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set parent
     *
     * @param Menu $parent
     * @return Menu
     */
    public function setParent(Menu $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return Menu 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set aclController
     *
     * @param AclController $aclController
     * @return Menu
     */
    public function setAclController(AclController $aclController = null)
    {
        $this->aclController = $aclController;
        return $this;
    }

    /**
     * Get aclController
     *
     * @return AclController 
     */
    public function getAclController()
    {
        return $this->aclController;
    }

    /**
     * Set aclAction
     *
     * @param AclAction $aclAction
     * @return Menu
     */
    public function setAclAction(AclAction $aclAction = null)
    {
        $this->aclAction = $aclAction;
        return $this;
    }

    /**
     * Get aclAction
     *
     * @return AclAction 
     */
    public function getAclAction()
    {
        return $this->aclAction;
    }
}