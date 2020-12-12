<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmpruntRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "order": {"createdAt":"asc"}
 *      },
 *     collectionOperations={"get"={"security"="is_granted('ROLE_ADMIN') or object.getUser() == user"},"post"={"security"="is_granted('ROLE_ADMIN')"}},
 *     itemOperations={"get"={"security"="is_granted('ROLE_ADMIN') or object.getUser() == user"},"delete"={"security"="is_granted('ROLE_ADMIN')"},"put"={"security"="is_granted('ROLE_ADMIN')"},"patch"={"security"="is_granted('ROLE_ADMIN')"}},
 *     denormalizationContext={"groups"={"emprunt:write"}}
 * )
 * @ApiFilter(
 *      SearchFilter::class, properties={"rendu":"partial","createdAt":"partial","User.nom":"partial","User.prenom":"partial","User.email":"partial"}
 * )
 * @ApiFilter(
 *      OrderFilter::class
 * )
 * @ORM\Entity(repositoryClass=EmpruntRepository::class)
 */
class Emprunt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"emprunt:write"})
     */
    private $rendu;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Emprunts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"emprunt:write"})
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Exemplaire::class, inversedBy="Emprunt")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"emprunt:write"})
     */
    private $exemplaire;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRendu()
    {
        return $this->rendu;
    }

    public function setRendu($rendu): self
    {
        $this->rendu = $rendu;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getExemplaire(): ?Exemplaire
    {
        return $this->exemplaire;
    }

    public function setExemplaire(?Exemplaire $exemplaire): self
    {
        $this->exemplaire = $exemplaire;

        return $this;
    }
}
