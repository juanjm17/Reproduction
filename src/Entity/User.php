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
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'iduser', targetEntity: SongsList::class)]
    private Collection $idlist;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Song::class)]
    private Collection $idsong;

    public function __construct()
    {
        $this->idlist = new ArrayCollection();
        $this->idsong = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

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

    /**
     * @return Collection<int, SongsList>
     */
    public function getIdlist(): Collection
    {
        return $this->idlist;
    }

    public function addIdlist(SongsList $idlist): static
    {
        if (!$this->idlist->contains($idlist)) {
            $this->idlist->add($idlist);
            $idlist->setIduser($this);
        }

        return $this;
    }

    public function removeIdlist(SongsList $idlist): static
    {
        if ($this->idlist->removeElement($idlist)) {
            // set the owning side to null (unless already changed)
            if ($idlist->getIduser() === $this) {
                $idlist->setIduser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, song>
     */
    public function getIdsong(): Collection
    {
        return $this->idsong;
    }

    public function addIdsong(Song $idsong): static
    {
        if (!$this->idsong->contains($idsong)) {
            $this->idsong->add($idsong);
            $idsong->setUser($this);
        }

        return $this;
    }

    public function removeIdsong(Song $idsong): static
    {
        if ($this->idsong->removeElement($idsong)) {
            // set the owning side to null (unless already changed)
            if ($idsong->getUser() === $this) {
                $idsong->setUser(null);
            }
        }

        return $this;
    }

    public function getSongslists(): Collection
    {
        $songs = new ArrayCollection();
        
        foreach ($this->idlist as $list) {
            foreach ($list->getSong() as $song) {
                $songs[] = $song;
            }
        }
        
        return $songs;
    }
    
    public function __toString(): string
{
    return $this->username;
}

}

