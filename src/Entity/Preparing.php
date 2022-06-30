<?php

namespace App\Entity;

use App\Repository\PreparingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PreparingRepository::class)
 */
class Preparing
{
    const PREPARING_VARS_LANG = ['schedule', 'month'];

    const PREPARING_VARS = [];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $schedule;

    /**
     * @ORM\Column(type="text")
     */
    private $month;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $enabled;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): self
    {
        $this->schedule = $schedule;

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
     * @return Preparing
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }
}
