<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $phone;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="users")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="user")
     */
    private $items;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Location", inversedBy="usersToMatch")
     * @ORM\JoinTable(name="users_locations")
     */
    private $locationsToMatch;

    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
        $this->locationsToMatch = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone(string $phone): User
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Set location
     *
     * @param Location $location
     *
     * @return User
     */
    public function setLocation(Location $location = null): User
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
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
     * Add locationToMatch
     *
     * @param Location $locationToMatch
     *
     * @return User
     */
    public function addLocationToMatch(Location $locationToMatch): User
    {
        $locationToMatch->addUserToMatch($this);
        $this->locationsToMatch[] = $locationToMatch;

        return $this;
    }

    /**
     * Remove locationToMatch
     *
     * @param Location $locationToMatch
     */
    public function removeLocationToMatch(Location $locationToMatch): void
    {
        $this->locationsToMatch->removeElement($locationToMatch);
    }

    /**
     * Get locations
     *
     * @return ArrayCollection
     */
    public function getLocationsToMatch(): ArrayCollection
    {
        return $this->locationsToMatch;
    }
}
