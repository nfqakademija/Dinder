<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
     */
    private $items;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="categoriesToMatch")
     */
    private $itemsToMatch;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->itemsToMatch = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Category
     */
    public function setTitle(string $title): Category
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getItems(): ArrayCollection
    {
        return $this->items;
    }

    /**
     * Add itemToMatch
     *
     * @param Item $itemToMatch
     *
     * @return Category
     */
    public function addItemToMatch(Item $itemToMatch): Category
    {
        $this->itemsToMatch[] = $itemToMatch;

        return $this;
    }

    /**
     * Remove itemToMatch
     *
     * @param Item $itemToMatch
     */
    public function removeItemToMatch(Item $itemToMatch): void
    {
        $this->itemsToMatch->removeElement($itemToMatch);
    }

    /**
     * Get itemsToMatch
     *
     * @return ArrayCollection
     */
    public function getItemsToMatch(): ArrayCollection
    {
        return $this->itemsToMatch;
    }
}
