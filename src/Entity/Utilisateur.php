<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'utilisateur')]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_utilisateur', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'mail_utilisateur', type: 'string', length: 255, nullable: true)]
    private ?string $userMail = null;

    #[ORM\Column(name: 'mot_de_passe', type: 'string', length: 255, nullable: true)]
    private ?string $motDePasse = null;

    #[ORM\Column(name: 'role', type: 'integer', nullable: true)]
    private ?int $role = null;

    #[ORM\Column(name: 'date_creation', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserMail(): ?string
    {
        return $this->userMail;
    }

    public function setUserMail(?string $userMail): static
    {
        $this->userMail = $userMail;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(?string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(?int $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
