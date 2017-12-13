<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Vich\Uploadable
 */
class Item
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;

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
     * @ORM\OneToMany(targetEntity="Image", mappedBy="item", cascade={"persist", "remove"}, orphanRemoval=true)
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

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
     * @var string
     *
     * @ORM\Column(name="imageName", type="string", length=255)
     */
    private $imageName;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="item_image", fileNameProperty="imageName")
     */
    private $file;

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
     * Collection of matches which are proposed by this item
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Match", mappedBy="itemOwner")
     */
    private $matchesOwnItem;

    /**
     * Collection of matches which are proposed to this item
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Match", mappedBy="itemRespondent")
     */
    private $matchesResponseItem;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="History", mappedBy="item")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    private $histories;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->categoriesToMatch = new ArrayCollection();
        $this->matchesOwnItem = new ArrayCollection();
        $this->matchesResponseItem = new ArrayCollection();
        $this->histories = new ArrayCollection();
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
     * @return \DateTimeInterface
     */
    public function getCreated(): ?\DateTimeInterface
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

        $image->setItem(null);
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
        $this->categoriesToMatch[ ] = $categoryToMatch;

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
     * @return Collection
     */
    public function getCategoriesToMatch(): Collection
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
        $result = [ ];

        foreach ($this->categoriesToMatch as $category) {
            $result[ ] = $category->getId();
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
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Add matchesOwnItem
     *
     * @param Match $match
     *
     * @return Item
     */
    public function addMatchesOwnItem(Match $match): Item
    {
        $this->matchesOwnItem[ ] = $match;

        return $this;
    }

    /**
     * Remove matchesOwnItem
     *
     * @param Match $match
     */
    public function removeMatchesOwnItem(Match $match): void
    {
        $this->matchesOwnItem->removeElement($match);
    }

    /**
     * Get matchesOwnItem
     *
     * @return Collection
     */
    public function getMatchesOwnItem(): Collection
    {
        return $this->matchesOwnItem;
    }

    /**
     * Add matchesResponseItem
     *
     * @param Match $match
     *
     * @return Item
     */
    public function addMatchesResponseItem(Match $match): Item
    {
        $this->matchesResponseItem[ ] = $match;

        return $this;
    }

    /**
     * Remove matchesResponseItem
     *
     * @param Match $match
     */
    public function removeMatchesResponseItem(Match $match): void
    {
        $this->matchesResponseItem->removeElement($match);
    }

    /**
     * Get matchesResponseItem
     *
     * @return Collection
     */
    public function getMatchesResponseItem(): Collection
    {
        return $this->matchesResponseItem;
    }

    /**
     * Get main image
     *
     * @return Image
     */
    public function getMainImage(): ?Image
    {
        foreach ($this->images->getIterator() as $image) {
            if ($image->getMain() === true) {
                return $image;
            }
        }
        if ($this->images->count()) {
            return $this->images->first();
        }

        return null;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Item
     */
    public function setDescription(string $description): Item
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Add history
     *
     * @param History $history
     *
     * @return Item
     */
    public function addHistory(History $history): Item
    {
        $this->histories[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param History $history
     */
    public function removeHistory(History $history): void
    {
        $this->histories->removeElement($history);
    }

    /**
     * Get histories
     *
     * @return Collection
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    /**
     * Get last history entity
     *
     * @return History
     */
    public function getLastHistory(): ?History
    {
        if ($this->histories->count()) {
            return $this->histories->first();
        } else {
            return null;
        }
    }

    /**
     * Set file
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return Image
     */
    public function setFile(File $file = null): Item
    {
        $this->file = $file;

        if ($file) {
            $this->created = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * Get file
     *
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Item
     */
    public function setImageName($imageName): Item
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
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
