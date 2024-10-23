<?php

namespace App\Entity;

use App\Repository\CampaignTracksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampaignTracksRepository::class)]
class CampaignTracks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $TrackTitle = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $AuthorTime = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'campaignTrackTimes')]
    private Collection $PlayerTimes;

    public function __construct()
    {
        $this->PlayerTimes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackTitle(): ?string
    {
        return $this->TrackTitle;
    }

    public function setTrackTitle(string $TrackTitle): static
    {
        $this->TrackTitle = $TrackTitle;

        return $this;
    }

    public function getAuthorTime(): ?\DateTimeImmutable
    {
        return $this->AuthorTime;
    }

    public function setAuthorTime(\DateTimeImmutable $AuthorTime): static
    {
        $this->AuthorTime = $AuthorTime;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPlayerTimes(): Collection
    {
        return $this->PlayerTimes;
    }

    public function addPlayerTime(User $playerTime): static
    {
        if (!$this->PlayerTimes->contains($playerTime)) {
            $this->PlayerTimes->add($playerTime);
            $playerTime->setCampaignTrackTimes($this);
        }

        return $this;
    }

    public function removePlayerTime(User $playerTime): static
    {
        if ($this->PlayerTimes->removeElement($playerTime)) {
            // set the owning side to null (unless already changed)
            if ($playerTime->getCampaignTrackTimes() === $this) {
                $playerTime->setCampaignTrackTimes(null);
            }
        }

        return $this;
    }
}
