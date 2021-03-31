<?php

namespace App\Entity;

use App\Repository\HwackRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Timestampable;

/**
 * @ORM\Entity(repositoryClass=HwackRepository::class)
 */
class Hwack implements Timestampable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="hwacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPrivate;

    /**
     * @ORM\OneToOne(targetEntity=HwackImage::class, mappedBy="hwack", cascade={"persist", "remove"})
     */
    private $hwackImage;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIsPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function setIsPrivate(bool $isPrivate): self
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    public function getHwackImage(): ?HwackImage
    {
        return $this->hwackImage;
    }

    public function setHwackImage(HwackImage $hwackImage): self
    {
        // set the owning side of the relation if necessary
        if ($hwackImage->getHwack() !== $this) {
            $hwackImage->setHwack($this);
        }

        $this->hwackImage = $hwackImage;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
}
