<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "pagination_enabled"=true,
 *          "order": {"titre":"asc"}
 *      },
 *     collectionOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"post"={"security"="is_granted('ROLE_ADMIN')"}},
 *     itemOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"delete"={"security"="is_granted('ROLE_ADMIN')"},"put"={"security"="is_granted('ROLE_ADMIN')"},"patch"={"security"="is_granted('ROLE_ADMIN')"}},
 *     normalizationContext={"groups"={"livre:read"}},
 *     denormalizationContext={"groups"={"livre:write"}}
 * )
 * @ApiFilter(
 *      SearchFilter::class, properties={"titre":"partial","anneePublication":"partial","genre.type":"partial","auteur.nom":"partial","auteur.prenom":"partial","editeur.nom":"partial"}
 * )
 * @ApiFilter(
 *      OrderFilter::class
 * )
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
     * @Groups({"livre:read","livre:write"})
     */
    private $titre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"livre:read","livre:write"})
     */
    private $nbPages;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"livre:read","livre:write"})
     */
    private $anneePublication;

    /**
     * @ORM\OneToMany(targetEntity=Exemplaire::class, mappedBy="livre", orphanRemoval=true, cascade={"persist"})
     * @Groups({"livre:read","livre:write"})
     */
    private $exemplaire;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="livre", cascade={"persist"})
     * @Groups({"livre:read","livre:write"})
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="livre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"livre:read","livre:write"})
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="livre", cascade={"persist"})
     * @Groups({"livre:read","livre:write"})
     */
    private $editeur;


    /**
     * @Groups({"livre:read"})
    */
    public function getCountExemplaires(): int
    {
        
        return count($this->exemplaire);
    }

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
     * @return Collection|Exemplaire[]
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
