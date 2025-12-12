<?php

namespace App\Entity;

use App\Repository\RestoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]

#[ORM\Entity(repositoryClass: RestoRepository::class)]
class Resto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(cascade: ["persist"], inversedBy: 'restos')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Chef $chef = null;


    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(
        min: 0,
        max: 3,
        notInRangeMessage: 'Le nombre d\'étoile doit être compris entre {{ min }} et {{ max }}',
    )]
    private ?int $nbEtoile = null;

    /**
     * @var Collection<int, Plat>
     */
    #[ORM\ManyToMany(targetEntity: Plat::class, inversedBy: 'restos', cascade: ['persist'])]
    private Collection $menu;

    public function __construct()
    {
        $this->menu = new ArrayCollection();
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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function corrigeNomResto()
    {
        $this->nom = strtoupper($this->nom);
    }

    public function getChef(): ?Chef
    {
        return $this->chef;
    }

    public function setChef(?Chef $chef): static
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    public function addMenu(Plat $menu): static
    {
        if (!$this->menu->contains($menu)) {
            $this->menu->add($menu);
        }

        return $this;
    }

    public function removeMenu(Plat $menu): static
    {
        $this->menu->removeElement($menu);

        return $this;
    }
}
