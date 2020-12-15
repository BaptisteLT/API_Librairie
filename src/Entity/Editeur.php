<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EditeurRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      attributes={
 *          "pagination_enabled"=true,
 *          "order": {"editeur":"nom"}
 *      },
 *     collectionOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"post"={"security"="is_granted('ROLE_ADMIN')"}},
 *     itemOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"delete"={"security"="is_granted('ROLE_ADMIN')"},"put"={"security"="is_granted('ROLE_ADMIN')"},"patch"={"security"="is_granted('ROLE_ADMIN')"}},
 *     denormalizationContext={"groups"={"editeur:write"}}
 * )
 * @ApiFilter(
 *      SearchFilter::class, properties={"nom":"partial"}
 * )
 * @ORM\Entity(repositoryClass=EditeurRepository::class)
 */
class Editeur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"livre:read","livre:write","editeur:write"})
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="editeur")
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
            $livre->setEditeur($this);
        }

        return $this;
    }

    public function removeLivre(livre $livre): self
    {
        if ($this->livre->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getEditeur() === $this) {
                $livre->setEditeur(null);
            }
        }

        return $this;
    }
}
