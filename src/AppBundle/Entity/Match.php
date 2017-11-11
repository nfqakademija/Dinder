<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="match")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MatchRepository")
 */
class Match
{
    public const STATUS_REJECTED = 0;
    public const STATUS_ACCEPTED = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="matches_own")
     * @ORM\JoinColumn(name="item_owner_id", referencedColumnName="id")
     */
    private $item_owner;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="matches_response")
     * @ORM\JoinColumn(name="item_respondent_id", referencedColumnName="id")
     */
    private $item_respondent;

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
     * Set status
     *
     * @param integer $status
     *
     * @return Match
     */
    public function setStatus(int $status): Match
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set itemOwner
     *
     * @param Item $itemOwner
     *
     * @return Match
     */
    public function setItemOwner(Item $itemOwner = null): Match
    {
        $this->item_owner = $itemOwner;

        return $this;
    }

    /**
     * Get itemOwner
     *
     * @return Item
     */
    public function getItemOwner(): Item
    {
        return $this->item_owner;
    }

    /**
     * Set itemRespondent
     *
     * @param Item $itemRespondent
     *
     * @return Match
     */
    public function setItemRespondent(Item $itemRespondent = null): Match
    {
        $this->item_respondent = $itemRespondent;

        return $this;
    }

    /**
     * Get itemRespondent
     *
     * @return Item
     */
    public function getItemRespondent(): Item
    {
        return $this->item_respondent;
    }
}
