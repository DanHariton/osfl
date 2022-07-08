<?php

namespace App\Entity;

use App\Repository\PreparingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PreparingRepository::class)
 */
class Preparing
{
    const PREPARING_VARS_LANG = ['month'];

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
    private $month;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="preparing")
     */
    private Collection $events;

    /**
     * Preparing constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function setEvents(ArrayCollection $events): void
    {
        $this->events = $events;
    }

    public function addEvent(Event $event) {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setPreparing($this);
        }

        return $this;
    }

    public function removeEvent(Event $event)
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getPreparing() === $this) {
                $event->setPreparing(null);
            }
        }

        return $this;
    }
}
