<?php

namespace OC\StockManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryTrees
 *
 * @ORM\Table(name="category_trees")
 * @ORM\Entity(repositoryClass="OC\StockManagementBundle\Repository\CategoryTreesRepository")
 */
class CategoryTrees
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="parentId", type="integer")
     */
    private $parentId;

        /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;


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
     * @return CategoryTrees
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
     * Set parentId.
     *
     * @param int $parentId
     *
     * @return CategoryTrees
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

        /**
     * Get position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

        /**
     * Set position.
     *
     * @param int $parentId
     *
     * @return CategoryTrees
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
