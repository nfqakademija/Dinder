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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="locationsToMatch")
     */
    private $usersToMatch;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->usersToMatch = new ArrayCollection();
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
     * Add usersToMatch
     *
     * @param User $usersToMatch
     *
     * @return Location
     */
    public function addUsersToMatch(User $usersToMatch): Location
    {
        $this->usersToMatch[] = $usersToMatch;

        return $this;
    }

    /**
     * Remove usersToMatch
     *
     * @param User $usersToMatch
     */
    public function removeUsersToMatch(User $usersToMatch): void
    {
        $this->usersToMatch->removeElement($usersToMatch);
    }

    /**
     * Get usersToMatch
     *
     * @return ArrayCollection
     */
    public function getUsersToMatch(): ArrayCollection
    {
        return $this->usersToMatch;
    }
}
