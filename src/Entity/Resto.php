<?php

namespace App\Entity;

use App\Repository\RestoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestoRepository::class)]
class Resto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 124)]
    private ?string $chef = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(
        min: 0,
        max: 3,
        notInRangeMessage: 'Le nombre d\'étoile doit être compris entre {{ min }} et {{ max }}',
    )]
    private ?int $nbEtoile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getChef(): ?string
    {
        return $this->chef;
    }

    public function setChef(string $chef): static
    {
        $this->chef = $chef;

        return $this;
    }

    public function getNbEtoile(): ?int
    {
        return $this->nbEtoile;
    }

    public function setNbEtoile(int $nbEtoile): static
    {
        $this->nbEtoile = $nbEtoile;

        return $this;
    }

    public function __toString()
    {
        return "Nom : " . $this->getNom() . "; Chef : " . $this->getChef() . "; Nb étoile : " . $this->getNbEtoile();
    }
}
