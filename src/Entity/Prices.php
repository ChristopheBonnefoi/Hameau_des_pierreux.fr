<?php

namespace App\Entity;

use App\Repository\PricesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PricesRepository::class)]
class Prices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $cottage;

    #[ORM\Column(type: 'text', nullable: true)]
    private $lodge;

    #[ORM\Column(type: 'text', nullable: true)]
    private $tent;

    #[ORM\Column(type: 'text', nullable: true)]
    private $breakfast;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCottage(): ?string
    {
        return $this->cottage;
    }

    public function setCottage(?string $cottage): self
    {
        $this->cottage = $cottage;

        return $this;
    }

    public function getLodge(): ?string
    {
        return $this->lodge;
    }

    public function setLodge(?string $lodge): self
    {
        $this->lodge = $lodge;

        return $this;
    }

    public function getTent(): ?string
    {
        return $this->tent;
    }

    public function setTent(?string $tent): self
    {
        $this->tent = $tent;

        return $this;
    }

    public function getBreakfast(): ?string
    {
        return $this->breakfast;
    }

    public function setBreakfast(?string $breakfast): self
    {
        $this->breakfast = $breakfast;

        return $this;
    }
}
