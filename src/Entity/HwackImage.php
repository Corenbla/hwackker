<?php

namespace App\Entity;

use App\Repository\HwackImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HwackImageRepository::class)
 */
class HwackImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\OneToOne(targetEntity=Hwack::class, inversedBy="hwackImage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $hwack;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getHwack(): ?Hwack
    {
        return $this->hwack;
    }

    public function setHwack(Hwack $hwack): self
    {
        $this->hwack = $hwack;

        return $this;
    }
}
