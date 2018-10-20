<?php

namespace OC\StockManagementBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="OC\StockManagementBundle\Repository\CategoryRepository")
 */
class Category
{

    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parentId", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

        /**
     * @var int
     *
     * @ORM\Column(name="wizaplaceId", type="integer")
     */
    private $wizaplaceId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="parentId", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var int
     *
     * @ORM\Column(name="lowStock", type="integer", nullable=true)
     */
    private $lowStock;

    public function getChildren() {
        return $this->children;
    }

    /**
     * Get lowStock.
     *
     * @return int
     */
    public function getLowStock()
    {
        return $this->lowStock;
    }

    /**
     * Set LowStock.
     *
     * @param int $lowStock
     *
     * @return Category
     */
    public function setLowStock($lowStock)
    {
        $this->lowStock = $lowStock;

        return $this;
    }


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
     * Set name.
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

        /**
     * Set id.
     *
     * @param int $id
     *
     * @return Category
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add child.
     *
     * @param \OC\StockManagementBundle\Entity\Category $child
     *
     * @return Category
     */
    public function addChild(\OC\StockManagementBundle\Entity\Category $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \OC\StockManagementBundle\Entity\Category $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\OC\StockManagementBundle\Entity\Category $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Set parent.
     *
     * @param \OC\StockManagementBundle\Entity\Category|null $parent
     *
     * @return Category
     */
    public function setParent(\OC\StockManagementBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \OC\StockManagementBundle\Entity\Category|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set wizaplaceId.
     *
     * @param int $wizaplaceId
     *
     * @return Category
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
     * Set parentId.
     *
     * @param int|null $parentId
     *
     * @return Category
     */
    public function setParentId($parentId = null)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId.
     *
     * @return int|null
     */
    public function getParentId()
    {
        return $this->parentId;
    }
}
