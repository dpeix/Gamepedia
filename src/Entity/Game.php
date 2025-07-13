<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $rawgId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 25500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $released = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $backgroundImage = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\Column(nullable: true)]
    private ?int $ratingCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $playtime = null;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'games')]
    private Collection $genres;

    /**
     * @var Collection<int, Platform>
     */
    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'games')]
    private Collection $platforms;

    /**
     * @var Collection<int, Developer>
     */
    #[ORM\ManyToMany(targetEntity: Developer::class, inversedBy: 'games')]
    private Collection $developers;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->platforms = new ArrayCollection();
        $this->developers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRawgId(): ?int
    {
        return $this->rawgId;
    }

    public function setRawgId(int $rawgId): static
    {
        $this->rawgId = $rawgId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getReleased(): ?\DateTime
    {
        return $this->released;
    }

    public function setReleased(?\DateTime $released): static
    {
        $this->released = $released;

        return $this;
    }

    public function getBackgroundImage(): ?string
    {
        return $this->backgroundImage;
    }

    public function setBackgroundImage(?string $backgroundImage): static
    {
        $this->backgroundImage = $backgroundImage;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getRatingCount(): ?int
    {
        return $this->ratingCount;
    }

    public function setRatingCount(?int $ratingCount): static
    {
        $this->ratingCount = $ratingCount;

        return $this;
    }

    public function getPlaytime(): ?int
    {
        return $this->playtime;
    }

    public function setPlaytime(?int $playtime): static
    {
        $this->playtime = $playtime;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Platform>
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): static
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms->add($platform);
        }

        return $this;
    }

    public function removePlatform(Platform $platform): static
    {
        $this->platforms->removeElement($platform);

        return $this;
    }

    /**
     * @return Collection<int, Developer>
     */
    public function getDevelopers(): Collection
    {
        return $this->developers;
    }

    public function addDeveloper(Developer $developer): static
    {
        if (!$this->developers->contains($developer)) {
            $this->developers->add($developer);
        }

        return $this;
    }

    public function removeDeveloper(Developer $developer): static
    {
        $this->developers->removeElement($developer);

        return $this;
    }
}
