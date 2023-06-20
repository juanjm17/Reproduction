<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\ManyToOne(inversedBy: 'idsong')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: SongsList::class, mappedBy: 'song')]
    private Collection $songslists;

    public function __construct()
    {
        $this->songslists = new ArrayCollection();
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Songslist>
     */
    public function getSongslists(): Collection
    {
        return $this->songslists;
    }

    public function addSongslist(SongsList $songslist): static
    {
        if (!$this->songslists->contains($songslist)) {
            $this->songslists->add($songslist);
            $songslist->addSong($this);
        }

        return $this;
    }

    public function removeSongslist(SongsList $songslist): static
    {
        if ($this->songslists->removeElement($songslist)) {
            $songslist->removeSong($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }
}
