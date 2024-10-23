<?php

namespace App\Entity;

use App\Repository\PlayerTracksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerTracksRepository::class)]
class PlayerTracks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $AuthorTime = null;

    #[ORM\Column(length: 255)]
    private ?string $Difficulty = null;

    #[ORM\Column(length: 255)]
    private ?string $tracktype = null;

    #[ORM\ManyToOne(inversedBy: 'playerTracks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    private ?string $Image = null;

    #[ORM\Column(length: 255)]
    private ?string $Replay = null;

    /**
     * @var Collection<int, PlayerReplays>
     */
    #[ORM\OneToMany(targetEntity: PlayerReplays::class, mappedBy: 'track')]
    private Collection $playerReplays;

    public function __construct()
    {
        $this->playerReplays = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthorTime(): ?\DateTimeInterface
    {
        return $this->AuthorTime;
    }

    public function setAuthorTime(\DateTimeInterface $AuthorTime): static
    {
        $this->AuthorTime = $AuthorTime;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->Difficulty;
    }

    public function setDifficulty(string $Difficulty): static
    {
        $this->Difficulty = $Difficulty;

        return $this;
    }

    public function getTracktype(): ?string
    {
        return $this->tracktype;
    }

    public function setTracktype(string $tracktype): static
    {
        $this->tracktype = $tracktype;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): static
    {
        $this->Image = $Image;

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

    /**
     * @return Collection<int, PlayerReplays>
     */
    public function getPlayerReplays(): Collection
    {
        return $this->playerReplays;
    }

    public function addPlayerReplay(PlayerReplays $playerReplay): static
    {
        if (!$this->playerReplays->contains($playerReplay)) {
            $this->playerReplays->add($playerReplay);
            $playerReplay->setTrack($this);
        }

        return $this;
    }

    public function removePlayerReplay(PlayerReplays $playerReplay): static
    {
        if ($this->playerReplays->removeElement($playerReplay)) {
            // set the owning side to null (unless already changed)
            if ($playerReplay->getTrack() === $this) {
                $playerReplay->setTrack(null);
            }
        }

        return $this;
    }
}
