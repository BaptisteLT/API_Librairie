<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExemplaireRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "pagination_enabled"=true,
 *          "order": {"dateAjout":"asc"}
 *      },
 *     collectionOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"post"={"security"="is_granted('ROLE_ADMIN')"}},
 *     itemOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"delete"={"security"="is_granted('ROLE_ADMIN')"},"put"={"security"="is_granted('ROLE_ADMIN')"},"patch"={"security"="is_granted('ROLE_ADMIN')"}},
 *     denormalizationContext={"groups"={"exemplaire:write"}}
 * )
 * @ApiFilter(
 *      OrderFilter::class
 * )
 * @ORM\Entity(repositoryClass=ExemplaireRepository::class)
 */
class Exemplaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"livre:read","livre:write"})
     */
    private $numExemplaire;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAjout;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="exemplaire")
     */
    private $emprunt;

    /**
     * @Groups({"exemplaire:write"})
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="exemplaire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    public function __construct()
    {
        $this->emprunt = new ArrayCollection();
        $this->dateAjout = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getNumExemplaire(): ?int
    {
        return $this->numExemplaire;
    }

    public function setNumExemplaire(int $numExemplaire): self
    {
        $this->numExemplaire = $numExemplaire;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(?\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * @return Collection|Emprunt[]
     */
    public function getEmprunt(): Collection
    {
        return $this->emprunt;
    }

    public function addEmprunt(emprunt $emprunt): self
    {
        if (!$this->emprunt->contains($emprunt)) {
            $this->emprunt[] = $emprunt;
            $emprunt->setExemplaire($this);
        }

        return $this;
    }

    public function removeEmprunt(emprunt $emprunt): self
    {
        if ($this->emprunt->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getExemplaire() === $this) {
                $emprunt->setExemplaire(null);
            }
        }

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }
}
