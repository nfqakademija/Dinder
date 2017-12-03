<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Category
 *
 * @ORM\Table(name="item_match")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MatchRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Match
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;

    public const STATUS_REJECTED = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_DECLINED = 2;

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
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="matchesOwnItem")
     * @ORM\JoinColumn(name="item_owner_id", referencedColumnName="id")
     */
    private $itemOwner;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="matchesResponseItem")
     * @ORM\JoinColumn(name="item_respondent_id", referencedColumnName="id")
     */
    private $itemRespondent;

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
        $this->itemOwner = $itemOwner;

        return $this;
    }

    /**
     * Get itemOwner
     *
     * @return Item
     */
    public function getItemOwner(): Item
    {
        return $this->itemOwner;
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
        $this->itemRespondent = $itemRespondent;

        return $this;
    }

    /**
     * Get itemRespondent
     *
     * @return Item
     */
    public function getItemRespondent(): Item
    {
        return $this->itemRespondent;
    }
}
