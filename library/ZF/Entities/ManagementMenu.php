<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementMenu
 *
 * @ORM\Table(name="management_menu")
 * @ORM\Entity
 */
class ManagementMenu
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var boolean $isDeleted
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     */
    private $isDeleted;

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
     * Set name
     *
     * @param string $name
     * @return ManagementMenu
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
     * @return ManagementMenu
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
     * Set aclController
     *
     * @param AclController $aclController
     * @return ManagementMenu
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
     * @return ManagementMenu
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