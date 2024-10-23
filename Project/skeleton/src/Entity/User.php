<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\ManyToOne(inversedBy: 'PlayerTimes')]
    private ?CampaignTracks $campaignTrackTimes = null;

    /**
     * @var Collection<int, PlayerTracks>
     */
    #[ORM\OneToMany(targetEntity: PlayerTracks::class, mappedBy: 'author')]
    private Collection $playerTracks;

    /**
     * @var Collection<int, PlayerReplays>
     */
    #[ORM\OneToMany(targetEntity: PlayerReplays::class, mappedBy: 'User')]
    private Collection $playerReplays;

    public function __construct()
    {
        $this->playerTracks = new ArrayCollection();
        $this->playerReplays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getCampaignTrackTimes(): ?CampaignTracks
    {
        return $this->campaignTrackTimes;
    }

    public function setCampaignTrackTimes(?CampaignTracks $campaignTrackTimes): static
    {
        $this->campaignTrackTimes = $campaignTrackTimes;

        return $this;
    }

    /**
     * @return Collection<int, PlayerTracks>
     */
    public function getPlayerTracks(): Collection
    {
        return $this->playerTracks;
    }

    public function addPlayerTrack(PlayerTracks $playerTrack): static
    {
        if (!$this->playerTracks->contains($playerTrack)) {
            $this->playerTracks->add($playerTrack);
            $playerTrack->setAuthor($this);
        }

        return $this;
    }

    public function removePlayerTrack(PlayerTracks $playerTrack): static
    {
        if ($this->playerTracks->removeElement($playerTrack)) {
            // set the owning side to null (unless already changed)
            if ($playerTrack->getAuthor() === $this) {
                $playerTrack->setAuthor(null);
            }
        }

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
            $playerReplay->setUser($this);
        }

        return $this;
    }

    public function removePlayerReplay(PlayerReplays $playerReplay): static
    {
        if ($this->playerReplays->removeElement($playerReplay)) {
            // set the owning side to null (unless already changed)
            if ($playerReplay->getUser() === $this) {
                $playerReplay->setUser(null);
            }
        }

        return $this;
    }
}