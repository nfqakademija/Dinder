<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 */
class Item
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;
    public const STATUS_TRADED = 3;

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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="items")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="item", cascade={"all"})
     */
    private $images;

    /**
     * @var int
     *
     * @ORM\Column(name="approvals", type="integer")
     */
    private $approvals;

    /**
     * @var int
     *
     * @ORM\Column(name="rejections", type="integer")
     */
    private $rejections;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetime")
     */
    private $expires;

    /**
     * Collection that holds the list of categories from which user prefers to find a match
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="itemsToMatch")
     * @ORM\JoinTable(name="items_categories")
     */
    private $categoriesToMatch;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Match", mappedBy="item_owner")
     */
    private $matches_own;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Match", mappedBy="item_respondent")
     */
    private $matches_response;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->categoriesToMatch = new ArrayCollection();
        $this->matches_own = new ArrayCollection();
        $this->matches_response = new ArrayCollection();
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
     * Set user
     *
     * @param User $user
     *
     * @return Item
     */
    public function setUser(User $user): Item
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
     * Set title
     *
     * @param string $title
     *
     * @return Item
     */
    public function setTitle(string $title): Item
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
     * Set value
     *
     * @param int $value
     *
     * @return Item
     */
    public function setValue(int $value): Item
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Item
     */
    public function setCategory(Category $category): Item
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Get images
     *
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * Set approvals
     *
     * @param int $approvals
     *
     * @return Item
     */
    public function setApprovals(int $approvals): Item
    {
        $this->approvals = $approvals;

        return $this;
    }

    /**
     * Get approvals
     *
     * @return int
     */
    public function getApprovals(): ?int
    {
        return $this->approvals;
    }

    /**
     * Set rejections
     *
     * @param int $rejections
     *
     * @return Item
     */
    public function setRejections(int $rejections): Item
    {
        $this->rejections = $rejections;

        return $this;
    }

    /**
     * Get rejections
     *
     * @return int
     */
    public function getRejections(): ?int
    {
        return $this->rejections;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Item
     */
    public function setCreated(\DateTime $created): Item
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
     * Set expires
     *
     * @param \DateTime $expires
     *
     * @return Item
     */
    public function setExpires(\DateTime $expires): Item
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires(): ?\DateTime
    {
        return $this->expires;
    }

    /**
     * Add image
     *
     * @param Image $image
     */
    public function addImage(Image $image): void
    {
        $image->setItem($this);

        $this->images->add($image);
    }

    /**
     * Remove image
     *
     * @param Image $image
     */
    public function removeImage(Image $image): void
    {
        $this->images->removeElement($image);
    }

    /**
     * Add categoriesToMatch
     *
     * @param Category $categoryToMatch
     *
     * @return Item
     */
    public function addCategoriesToMatch(Category $categoryToMatch): Item
    {
        $categoryToMatch->addItemsToMatch($this);
        $this->categoriesToMatch[] = $categoryToMatch;

        return $this;
    }

    /**
     * Remove categoryToMatch
     *
     * @param Category $categoryToMatch
     */
    public function removeCategoriesToMatch(Category $categoryToMatch): void
    {
        $this->categoriesToMatch->removeElement($categoryToMatch);
    }

    /**
     * Get categoriesToMatch
     *
     * @return ArrayCollection
     */
    public function getCategoriesToMatch(): ?ArrayCollection
    {
        return $this->categoriesToMatch;
    }

    /**
     * Get categoriesToMatchArray
     *
     * @return array
     */
    public function getCategoriesToMatchArray(): array
    {
        $result = [];

        foreach ($this->categoriesToMatch as $category) {
            $result[] = $category->getId();
        }

        return $result;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Item
     */
    public function setStatus(int $status): Item
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
     * Add matchesOwn
     *
     * @param Match $match
     *
     * @return Item
     */
    public function addMatchesOwn(Match $match): Item
    {
        $this->matches_own[] = $match;

        return $this;
    }

    /**
     * Remove matchesOwn
     *
     * @param Match $match
     */
    public function removeMatchesOwn(Match $match): void
    {
        $this->matches_own->removeElement($match);
    }

    /**
     * Get matchesOwn
     *
     * @return ArrayCollection
     */
    public function getMatchesOwn(): ArrayCollection
    {
        return $this->matches_own;
    }

    /**
     * Add matchesResponse
     *
     * @param Match $match
     *
     * @return Item
     */
    public function addMatchesResponse(Match $match): Item
    {
        $this->matches_response[] = $match;

        return $this;
    }

    /**
     * Remove matchesResponse
     *
     * @param Match $match
     */
    public function removeMatchesResponse(Match $match): void
    {
        $this->matches_response->removeElement($match);
    }

    /**
     * Get matchesResponse
     *
     * @return ArrayCollection
     */
    public function getMatchesResponse(): ArrayCollection
    {
        return $this->matches_response;
    }
}
