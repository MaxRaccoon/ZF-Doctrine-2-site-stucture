<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * ManagementMenuRel
 *
 * @ORM\Table(name="management_menu_rel")
 * @ORM\Entity
 */
class ManagementMenuRel
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
     * @var ManagementMenu
     *
     * @ORM\OneToOne(targetEntity="ManagementMenu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="management_menu_id", referencedColumnName="id", unique=true)
     * })
     */
    private $managementMenu;

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
     * Set managementMenu
     *
     * @param ManagementMenu $managementMenu
     * @return ManagementMenuRel
     */
    public function setManagementMenu(ManagementMenu $managementMenu = null)
    {
        $this->managementMenu = $managementMenu;
        return $this;
    }

    /**
     * Get managementMenu
     *
     * @return ManagementMenu 
     */
    public function getManagementMenu()
    {
        return $this->managementMenu;
    }

    /**
     * Set aclRole
     *
     * @param AclRole $aclRole
     * @return ManagementMenuRel
     */
    public function setAclRole(AclRole $aclRole = null)
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