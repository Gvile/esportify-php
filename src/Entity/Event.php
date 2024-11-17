<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $title = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $maxUser = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?User $ownerUser = null;

    #[ORM\Column]
    private bool $isValidated;

    /**
     * @var Collection<int, EventImage>
     */
    #[ORM\OneToMany(targetEntity: EventImage::class, mappedBy: 'eventId')]
    private Collection $eventImages;

    /**
     * @var Collection<int, EventUser>
     */
    #[ORM\OneToMany(targetEntity: EventUser::class, mappedBy: 'event')]
    private Collection $eventUsers;

    public function __construct()
    {
        $this->eventImages = new ArrayCollection();
        $this->eventUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxUser(): ?int
    {
        return $this->maxUser;
    }

    public function setMaxUser(int $maxUser): static
    {
        $this->maxUser = $maxUser;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getOwnerUser(): ?User
    {
        return $this->ownerUser;
    }

    public function setOwnerUser(?User $ownerUserId): static
    {
        $this->ownerUser = $ownerUserId;

        return $this;
    }

    /**
     * @return Collection<int, EventImage>
     */
    public function getEventImages(): Collection
    {
        return $this->eventImages;
    }

    public function addEventImage(EventImage $eventImage): static
    {
        if (!$this->eventImages->contains($eventImage)) {
            $this->eventImages->add($eventImage);
            $eventImage->setEvent($this);
        }

        return $this;
    }

    public function removeEventImage(EventImage $eventImage): static
    {
        if ($this->eventImages->removeElement($eventImage)) {
            // set the owning side to null (unless already changed)
            if ($eventImage->getEvent() === $this) {
                $eventImage->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventUser>
     */
    public function getEventUsers(): Collection
    {
        return $this->eventUsers;
    }

    public function addEventUser(EventUser $eventUser): static
    {
        if (!$this->eventUsers->contains($eventUser)) {
            $this->eventUsers->add($eventUser);
            $eventUser->setEvent($this);
        }

        return $this;
    }

    public function removeEventUser(EventUser $eventUser): static
    {
        if ($this->eventUsers->removeElement($eventUser)) {
            // set the owning side to null (unless already changed)
            if ($eventUser->getEvent() === $this) {
                $eventUser->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of isValidated
     *
     * @return bool
     */
    public function getIsValidated(): bool
    {
        return $this->isValidated;
    }

    /**
     * Set the value of isValidated
     *
     * @param bool $isValidated
     * @return self
     */
    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }
}
