<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity
 */
class History
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
     * Previous item user is stored
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="histories")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="histories")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="seen", type="datetime", nullable=true)
     */
    private $seen;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->created = new \DateTime();
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return History
     */
    public function setUser(User $user = null): History
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set item
     *
     * @param Item $item
     *
     * @return History
     */
    public function setItem(Item $item = null): History
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return Item
     */
    public function getItem(): ?Item
    {
        return $this->item;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return History
     */
    public function setCreated($created): History
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * Set seen
     *
     * @param \DateTime $seen
     *
     * @return History
     */
    public function setSeen($seen): History
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return \DateTime
     */
    public function getSeen(): ?\DateTime
    {
        return $this->seen;
    }
}
