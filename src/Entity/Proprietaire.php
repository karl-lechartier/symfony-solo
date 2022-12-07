<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProprietaireRepository::class)
 */
class Proprietaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Chaton::class, mappedBy="proprietaires")
     */
    private $chatons;

    public function __construct()
    {
        $this->chatons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Chaton>
     */
    public function getChatons(): Collection
    {
        return $this->chatons;
    }

    public function addChaton(Chaton $chaton): self
    {
        if (!$this->chatons->contains($chaton)) {
            $this->chatons[] = $chaton;
            $chaton->addProprietaire($this);
        }

        return $this;
    }

    public function removeChaton(Chaton $chaton): self
    {
        if ($this->chatons->removeElement($chaton)) {
            $chaton->removeProprietaire($this);
        }

        return $this;
    }
}
