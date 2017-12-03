<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Location
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
     * @param User $userToMatch
     *
     * @return Location
     */
    public function addUsersToMatch(User $userToMatch): Location
    {
        $this->usersToMatch[] = $userToMatch;

        return $this;
    }

    /**
     * Remove usersToMatch
     *
     * @param User $userToMatch
     */
    public function removeUsersToMatch(User $userToMatch): void
    {
        $this->usersToMatch->removeElement($userToMatch);
    }

    /**
     * Get usersToMatch
     *
     * @return Collection
     */
    public function getUsersToMatch(): Collection
    {
        return $this->usersToMatch;
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
