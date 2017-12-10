<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Category
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;

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
    public function getId(): ?int
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Get items
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * Add itemsToMatch
     *
     * @param Item $itemToMatch
     *
     * @return Category
     */
    public function addItemsToMatch(Item $itemToMatch): Category
    {
        $this->itemsToMatch[ ] = $itemToMatch;

        return $this;
    }

    /**
     * Remove itemToMatch
     *
     * @param Item $itemToMatch
     */
    public function removeItemsToMatch(Item $itemToMatch): void
    {
        $this->itemsToMatch->removeElement($itemToMatch);
    }

    /**
     * Get itemsToMatch
     *
     * @return Collection
     */
    public function getItemsToMatch(): Collection
    {
        return $this->itemsToMatch;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}
