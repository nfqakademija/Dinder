<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity
 */
class Location
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
     * @ORM\OneToMany(targetEntity="User", mappedBy="location")
     */
    private $users;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="locations")
     */
    private $locationsToFilter;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->locationsToFilter = new ArrayCollection();
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
     * @return Location
     */
    public function setTitle(string $title): Location
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
     * Add locationsToFilter
     *
     * @param User $locationsToFilter
     *
     * @return Location
     */
    public function addLocationsToFilter(User $locationsToFilter): Location
    {
        $this->locationsToFilter[] = $locationsToFilter;

        return $this;
    }

    /**
     * Remove locationsToFilter
     *
     * @param User $locationsToFilter
     */
    public function removeLocationsToFilter(User $locationsToFilter): void
    {
        $this->locationsToFilter->removeElement($locationsToFilter);
    }

    /**
     * Get locationsToFilter
     *
     * @return ArrayCollection
     */
    public function getLocationsToFilter(): ArrayCollection
    {
        return $this->locationsToFilter;
    }
}
