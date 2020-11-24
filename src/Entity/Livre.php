<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbPages;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $anneePublication;

    /**
     * @ORM\OneToMany(targetEntity=exemplaire::class, mappedBy="livre", orphanRemoval=true)
     */
    private $exemplaire;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="livre")
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="livre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="livre")
     */
    private $editeur;

    public function __construct()
    {
        $this->exemplaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getNbPages(): ?int
    {
        return $this->nbPages;
    }

    public function setNbPages(?int $nbPages): self
    {
        $this->nbPages = $nbPages;

        return $this;
    }

    public function getAnneePublication(): ?\DateTimeInterface
    {
        return $this->anneePublication;
    }

    public function setAnneePublication(?\DateTimeInterface $anneePublication): self
    {
        $this->anneePublication = $anneePublication;

        return $this;
    }

    /**
     * @return Collection|exemplaire[]
     */
    public function getExemplaire(): Collection
    {
        return $this->exemplaire;
    }

    public function addExemplaire(exemplaire $exemplaire): self
    {
        if (!$this->exemplaire->contains($exemplaire)) {
            $this->exemplaire[] = $exemplaire;
            $exemplaire->setLivre($this);
        }

        return $this;
    }

    public function removeExemplaire(exemplaire $exemplaire): self
    {
        if ($this->exemplaire->removeElement($exemplaire)) {
            // set the owning side to null (unless already changed)
            if ($exemplaire->getLivre() === $this) {
                $exemplaire->setLivre(null);
            }
        }

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }
}
