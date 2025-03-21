<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: OffreDeStage::class)]
    private Collection $offresDeStage;

    public function __construct()
    {
        $this->offresDeStage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, OffreDeStage>
     */
    public function getOffresDeStage(): Collection
    {
        return $this->offresDeStage;
    }

    public function addOffresDeStage(OffreDeStage $offresDeStage): static
    {
        if (!$this->offresDeStage->contains($offresDeStage)) {
            $this->offresDeStage->add($offresDeStage);
            $offresDeStage->setEntreprise($this);
        }

        return $this;
    }

    public function removeOffresDeStage(OffreDeStage $offresDeStage): static
    {
        if ($this->offresDeStage->removeElement($offresDeStage)) {
            // set the owning side to null (unless already changed)
            if ($offresDeStage->getEntreprise() === $this) {
                $offresDeStage->setEntreprise(null);
            }
        }

        return $this;
    }
}
