<?php

namespace App\Entity;

use App\Repository\SliderImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SliderImagesRepository::class)
 */
class SliderImages
{
    const SLIDER_VARS_LANG = ['mainText', 'smallText'];

    const SLIDER_VARS = ['altName', 'displayOrder'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageFilename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $altName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $displayOrder;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $enabled;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mainText;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $smallText;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    /**
     * @param mixed $imageFilename
     * @return SliderImages
     */
    public function setImageFilename($imageFilename)
    {
        $this->imageFilename = $imageFilename;
        return $this;
    }

    public function getAltName(): ?string
    {
        return $this->altName;
    }

    public function setAltName(string $altName): self
    {
        $this->altName = $altName;

        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $displayOrder): self
    {
        $this->displayOrder = $displayOrder;

        return $this;
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
     * @return SliderImages
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getMainText(): ?string
    {
        return $this->mainText;
    }

    public function setMainText(?string $mainText): self
    {
        $this->mainText = $mainText;

        return $this;
    }

    public function getSmallText(): ?string
    {
        return $this->smallText;
    }

    public function setSmallText(?string $smallText): self
    {
        $this->smallText = $smallText;

        return $this;
    }
}
