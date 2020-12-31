<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuteurRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "pagination_enabled"=true,
 *          "order": {"prenom","nom","dateNaissance"},
 *      },
 *     collectionOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"post"={"security"="is_granted('ROLE_ADMIN')"}},
 *     itemOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"delete"={"security"="is_granted('ROLE_ADMIN')"},"put"={"security"="is_granted('ROLE_ADMIN')"},"patch"={"security"="is_granted('ROLE_ADMIN')"}},
 *     denormalizationContext={"groups"={"auteur:write"}}
 * )
 * @ApiFilter(
 *      SearchFilter::class, properties={"nom":"partial","prenom":"partial","dateNaissance":"partial","livre.titre":"partial"}
 * )
 * @ApiFilter(
 *      OrderFilter::class
 * )
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 */
class Auteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post"})
     * @Groups({"livre:read","livre:write","auteur:write"})
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"livre:read","livre:write","auteur:write"})
     * @Assert\NotBlank
     */
    private $prenom;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"livre:read","livre:write","auteur:write"})
     * @Assert\NotBlank
     */
    private $dateNaissance;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="auteur")
     */
    private $livre;

    public function __construct()
    {
        $this->livre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivre(): Collection
    {
        return $this->livre;
    }

    public function addLivre(livre $livre): self
    {
        if (!$this->livre->contains($livre)) {
            $this->livre[] = $livre;
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(livre $livre): self
    {
        if ($this->livre->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }
}
