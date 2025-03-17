<?php
// src/Entity/Offre.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Offre
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private $id;

#[ORM\Column(type: 'string', length: 255)]
private $nom;

#[ORM\Column(type: 'text')]
private $description;

// Ajoutez d'autres propriétés selon vos besoins
// Getters et setters
}
?>