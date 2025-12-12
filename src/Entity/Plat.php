<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    /**
     * @var Collection<int, Resto>
     */
    #[ORM\ManyToMany(targetEntity: Resto::class, mappedBy: 'plat', cascade: ['persist'])]
    private Collection $restos;

    public function __construct()
    {
        $this->restos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * @return Collection<int, Resto>
     */
    public function getRestos(): Collection
    {
        return $this->restos;
    }

    public function addResto(Resto $resto): static
    {
        if (!$this->restos->contains($resto)) {
            $this->restos->add($resto);
            $resto->addPlat($this);
        }

        return $this;
    }

    public function removeResto(Resto $resto): static
    {
        if ($this->restos->removeElement($resto)) {
            $resto->removePlat($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
    }
}
