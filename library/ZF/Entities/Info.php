<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * Info
 *
 * @ORM\Table(name="info")
 * @ORM\Entity
 */
class Info
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
     * @var string $infoKey
     *
     * @ORM\Column(name="info_key", type="string", length=50, nullable=false)
     */
    private $infoKey;

    /**
     * @var string $infoValue
     *
     * @ORM\Column(name="info_value", type="string", length=250, nullable=false)
     */
    private $infoValue;


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
     * Set infoKey
     *
     * @param string $infoKey
     * @return Info
     */
    public function setInfoKey($infoKey)
    {
        $this->infoKey = $infoKey;
        return $this;
    }

    /**
     * Get infoKey
     *
     * @return string 
     */
    public function getInfoKey()
    {
        return $this->infoKey;
    }

    /**
     * Set infoValue
     *
     * @param string $infoValue
     * @return Info
     */
    public function setInfoValue($infoValue)
    {
        $this->infoValue = $infoValue;
        return $this;
    }

    /**
     * Get infoValue
     *
     * @return string 
     */
    public function getInfoValue()
    {
        return $this->infoValue;
    }
}