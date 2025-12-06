<?php

namespace App\Entity;

use App\Repository\ChefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChefRepository::class)]
class Chef
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'chef', targetEntity: Resto::class)]
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
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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
            $resto->setChef($this);
        }

        return $this;
    }

    public function removeResto(Resto $resto): static
    {
        if ($this->restos->removeElement($resto)) {
            // set the owning side to null (unless already changed)
            if ($resto->getChef() === $this) {
                $resto->setChef(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return $this->nom . " " . $this->prenom;
    }
}
