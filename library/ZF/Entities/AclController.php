<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;
/**
 * AclController
 *
 * @ORM\Table(name="acl_controller")
 * @ORM\Entity
 */
class AclController implements \Zend_Acl_Resource_Interface
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
     * @return AclController
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
     * @return AclController
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
     * #####################
     * ####NO DB############
     * #####################
     */

    /**
     * For Acl
     * @return string
     */
    public function getResourceId()
    {
        return "controller-" . $this->getName();
    }    
}