<?php
namespace ZF\Entities;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
    
/**
 * PortfolioPicRel
 *
 * @ORM\Table(name="portfolio_pic_rel")
 * @ORM\Entity
 */
class PortfolioPicRel
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
     * @var Picture
     *
     * @ORM\OneToOne(targetEntity="Picture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pic_id", referencedColumnName="id", unique=true)
     * })
     */
    private $pic;

    /**
     * @var Portfolio
     *
     * @ORM\OneToOne(targetEntity="Portfolio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="portfolio_id", referencedColumnName="id", unique=true)
     * })
     */
    private $portfolio;


    function __construct()
    {
        $this->pic = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
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
     * Set pic
     *
     * @param Picture $pic
     * @return PortfolioPicRel
     */
    public function setPic(Picture $pic = null)
    {
        $this->pic = $pic;
        return $this;
    }

    /**
     * Get pic
     *
     * @return Picture 
     */
    public function getPic()
    {
        return $this->pic;
    }

    /**
     * Set portfolio
     *
     * @param Portfolio $portfolio
     * @return PortfolioPicRel
     */
    public function setPortfolio(Portfolio $portfolio = null)
    {
        $this->portfolio = $portfolio;
        return $this;
    }

    /**
     * Get portfolio
     *
     * @return Portfolio 
     */
    public function getPortfolio()
    {
        return $this->portfolio;
    }
}