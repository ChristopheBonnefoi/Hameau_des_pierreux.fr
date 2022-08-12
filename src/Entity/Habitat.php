<?php

namespace App\Entity;

use App\Repository\HabitatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitatRepository::class)]
class Habitat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $title;

    #[ORM\Column(type: 'string', length: 200)]
    private $description;

    #[ORM\OneToMany(mappedBy: 'habitat', targetEntity: HabitatImages::class, cascade: ['persist'], orphanRemoval: true)]
    private $habitatImages;

    #[ORM\Column(type: 'string', length: 255)]
    private $coverImage;

    #[ORM\Column(type: 'string', length: 150)]
    private $places;

    #[ORM\Column(type: 'text')]
    private $content;

    public function __construct()
    {
        $this->habitatImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, HabitatImages>
     */
    public function getHabitatImages(): Collection
    {
        return $this->habitatImages;
    }

    public function addHabitatImage(HabitatImages $habitatImage): self
    {
        if (!$this->habitatImages->contains($habitatImage)) {
            $this->habitatImages[] = $habitatImage;
            $habitatImage->setHabitat($this);
        }

        return $this;
    }

    public function removeHabitatImage(HabitatImages $habitatImage): self
    {
        if ($this->habitatImages->removeElement($habitatImage)) {
            // définir le côté propriétaire sur null (sauf si déjà modifié)
            if ($habitatImage->getHabitat() === $this) {
                $habitatImage->setHabitat(null);
            }
        }

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getPlaces(): ?string
    {
        return $this->places;
    }

    public function setPlaces(string $places): self
    {
        $this->places = $places;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
