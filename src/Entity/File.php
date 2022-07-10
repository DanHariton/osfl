<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
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
    private $fileName;

    /**
     * @ORM\Column(name="`order`", type="integer", nullable=true)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class, inversedBy="images")
     */
    private $offerImage;

    /**
     * @ORM\ManyToOne(targetEntity=OfferImages::class, inversedBy="images")
     */
    private $offerImageContainer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getOfferImage(): ?Offer
    {
        return $this->offerImage;
    }

    public function setOfferImage(?Offer $offer): self
    {
        $this->offerImage = $offer;

        return $this;
    }

    public function getOfferImageContainer(): ?OfferImages
    {
        return $this->offerImageContainer;
    }

    public function setOfferImageContainer(?OfferImages $offerImage): self
    {
        $this->offerImageContainer = $offerImage;

        return $this;
    }
}
