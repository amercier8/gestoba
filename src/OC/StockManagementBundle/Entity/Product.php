<?php

namespace OC\StockManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="OC\StockManagementBundle\Repository\ProductRepository")
 */
class Product
{
    /**
    * @ORM\OneToOne(targetEntity="OC\StockManagementBundle\Entity\Category")
    * @ORM\JoinColumn(nullable=false)
    */
    private $category;

    public function setCategory(Category $category = null)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;  
    }


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="wizaplaceId", type="integer", unique=true)
     */
    private $wizaplaceId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     */
    private $stock;

    /**
     * @var int
     *
     * @ORM\Column(name="categoryIdWizaplace", type="integer")
     */
    private $categoryIdWizaplace;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set wizaplaceId.
     *
     * @param int $wizaplaceId
     *
     * @return Product
     */
    public function setWizaplaceId($wizaplaceId)
    {
        $this->wizaplaceId = $wizaplaceId;

        return $this;
    }

    /**
     * Get wizaplaceId.
     *
     * @return int
     */
    public function getWizaplaceId()
    {
        return $this->wizaplaceId;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Product
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set stock.
     *
     * @param int|null $stock
     *
     * @return Product
     */
    public function setStock($stock = null)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock.
     *
     * @return int|null
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set categoryIdWizaplace.
     *
     * @param int $categoryIdWizaplace
     *
     * @return Product
     */
    public function setCategoryIdWizaplace($categoryIdWizaplace)
    {
        $this->categoryIdWizaplace = $categoryIdWizaplace;

        return $this;
    }

    /**
     * Get categoryIdWizaplace.
     *
     * @return int
     */
    public function getCategoryIdWizaplace()
    {
        return $this->categoryIdWizaplace;
    }
}
