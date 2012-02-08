<?php
namespace ZF\Entites;
use Doctrine\ORM\Mapping as ORM;

/**
 * AclGroupDeny
 *
 * @ORM\Table(name="acl_group_deny")
 * @ORM\Entity
 */
class AclGroupDeny
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
     * @var AclRole
     *
     * @ORM\OneToOne(targetEntity="AclRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="acl_role_id", referencedColumnName="id", unique=true)
     * })
     */
    private $aclRole;


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
     * Set aclController
     *
     * @param AclController $aclController
     * @return AclGroupDeny
     */
    public function setAclController(\AclController $aclController = null)
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
     * @return AclGroupDeny
     */
    public function setAclAction(\AclAction $aclAction = null)
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

    /**
     * Set aclRole
     *
     * @param AclRole $aclRole
     * @return AclGroupDeny
     */
    public function setAclRole(\AclRole $aclRole = null)
    {
        $this->aclRole = $aclRole;
        return $this;
    }

    /**
     * Get aclRole
     *
     * @return AclRole 
     */
    public function getAclRole()
    {
        return $this->aclRole;
    }
}