<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @Vich\Uploadable
 */
class Image
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
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="images")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="item_image", fileNameProperty="name", size="size")
     */
    private $file;

    /**
     * @var bool
     *
     * @ORM\Column(name="main", type="boolean")
     */
    private $main = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


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
     * Set item
     *
     * @param Item $item
     *
     * @return Image
     */
    public function setItem(Item $item): Image
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName(string $name = null): Image
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
     * Set size
     *
     * @param int $size
     *
     * @return Image
     */
    public function setSize(int $size = null): Image
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Set file
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return Image
     */
    public function setFile(File $file = null): Image
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
     * Set main
     *
     * @param bool $main
     *
     * @return Image
     */
    public function setMain(bool $main): Image
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return bool
     */
    public function getMain(): bool
    {
        return $this->main;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Image
     */
    public function setCreated(\DateTime $created): Image
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }
}

