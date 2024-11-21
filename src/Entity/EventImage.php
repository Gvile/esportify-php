<?php

namespace App\Entity;

use App\Repository\EventImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: EventImageRepository::class)]
#[Vich\Uploadable]
class EventImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'event', fileNameProperty: 'name', size: 'size')]
    private ?File $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'eventImages')]
    private ?Event $event = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $eventId): static
    {
        $this->event = $eventId;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        if (null != $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * Get the value of name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of size
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * Set the value of size
     */
    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }
}
