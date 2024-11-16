<?php

namespace App\Entity;

use App\Repository\EventUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventUserRepository::class)]
class EventUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventUsers')]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'eventUsers')]
    private ?User $user_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getUserParticipant(): ?User
    {
        return $this->user_participant;
    }

    public function setUserParticipant(?User $user_participant): static
    {
        $this->user_participant = $user_participant;

        return $this;
    }
}
