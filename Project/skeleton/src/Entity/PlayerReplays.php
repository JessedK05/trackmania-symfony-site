<?php

namespace App\Entity;

use App\Repository\PlayerReplaysRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerReplaysRepository::class)]
class PlayerReplays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'playerReplays')]
    private ?User $User = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $TimeSet = null;

    #[ORM\Column(length: 255)]
    private ?string $Replay = null;

    #[ORM\ManyToOne(inversedBy: 'playerReplays')]
    private ?PlayerTracks $track = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getTimeSet(): ?\DateTimeInterface
    {
        return $this->TimeSet;
    }

    public function setTimeSet(\DateTimeInterface $TimeSet): static
    {
        $this->TimeSet = $TimeSet;

        return $this;
    }

    public function getReplay(): ?string
    {
        return $this->Replay;
    }

    public function setReplay(string $Replay): static
    {
        $this->Replay = $Replay;

        return $this;
    }

    public function getTrack(): ?PlayerTracks
    {
        return $this->track;
    }

    public function setTrack(?PlayerTracks $track): static
    {
        $this->track = $track;

        return $this;
    }
}
