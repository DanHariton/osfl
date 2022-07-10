<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
class Offer
{
    const OFFER_VARS_LANG = ['name', 'description'];

    const OFFER_VARS = ['orderShow'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="offerImage")
     */
    private $images;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderShow;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @return array|File[]
     */
    public function getOrderedImages(): array
    {
        $images = $this->images->getValues();

        usort($images, function (File $left, File $right) {
            return $left->getOrder() <=> $right->getOrder();
        });

        return array_values($images);
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     * @return Offer
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getOrderedImagesPaths()
    {
        return array_map(function (File $file) {
            return $file->getFileName();
        }, $this->getOrderedImages());
    }

    public function getPreview()
    {
        return $this->getOrderedImagesPaths()[0] ?? null;
    }

    public function getMaxOrder()
    {
        $orders = array_map(function (File $file) {
            return $file->getOrder();
        }, $this->images->getValues());

        if (empty($orders)) {
            return 1;
        }

        return max($orders) + 1;
    }

    public function reorder(File $file, $way)
    {
        $orderedImages = $this->getOrderedImages();
        $maxOrder = count($orderedImages);
        foreach ($orderedImages as $index => $image) {
            if ($image->getId() === $file->getId()) {
                $index += $way;
            }
            $index = $index === $maxOrder ? $index - 1 : $index;
            $index = $index === -1 ? 0 : $index;
            $image->setOrder($index);
        }

        foreach ($orderedImages as $index => $image) {
            if ($index > 0 && $orderedImages[$index-1]->getOrder() === $image->getOrder()) {
                if ($way === 1) {
                    $image->setOrder($image->getOrder() - 1);
                }
                if ($way === -1) {
                    $orderedImages[$index-1]->setOrder($image->getOrder() + 1);
                }
            }
        }
    }

    public function addImage(File $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setOfferImage($this);
        }

        return $this;
    }

    public function removeImage(File $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getOfferImage() === $this) {
                $image->setOfferImage(null);
            }
        }

        return $this;
    }

    public function getOrderShow(): ?int
    {
        return $this->orderShow;
    }

    public function setOrderShow(int $orderShow): self
    {
        $this->orderShow = $orderShow;

        return $this;
    }
}
