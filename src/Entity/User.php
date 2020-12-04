<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      attributes={
 *          "pagination_enabled"=true,
 *          "order": {"nom":"asc"}
 *      },
 *     collectionOperations={"get"={"security"="is_granted('ROLE_ADMIN')"},"post"={"denormalization_context"={"groups"={"user:write"}}}},
 *     itemOperations={"passwordChange"={"security" = "is_granted('USER_PATCH', object)","denormalization_context"={"groups"={"customOperationPassword:write"}},"method"="patch", "path"="/users/{id}/passwordchange","controller"="App\Controller\UserChangePasswordController"},"get" = { "security" = "is_granted('USER_GET', object)" },"delete"={"security"="is_granted('ROLE_ADMIN')"},"put" = {"denormalization_context"={"groups"={"user:write"}}, "security" = "is_granted('USER_PUT', object)" },"patch" = {"denormalization_context"={"groups"={"user:write"}}, "security" = "is_granted('USER_PATCH', object)" }},
 *     normalizationContext={"groups"={"user:read"}},
 *     
 * )
 * @ApiFilter(
 *      SearchFilter::class, properties={"nom":"partial","email":"partial","etablissement":"partial","classe":"partial","prenom":"partial","dateNaissance":"partial","adresse":"partial","createdAt":"partial"}
 * )
 * @ApiFilter(
 *      OrderFilter::class
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read","user:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read","user:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read","admin:write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:write"})
     */
    private $password;

    /**
     * @Groups({"customOperationPassword:write"})
     */
    private $newPassword;

    /**
     * @Groups({"customOperationPassword:write"})
     */
    private $oldPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write"})
     */
    private $etablissement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write"})
     */
    private $classe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read","user:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read","user:write"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     * @Groups({"user:read","user:write"})
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read","user:write"})
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read","user:write"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="User")
     * @Groups({"user:read","admin:write"})
     */
    private $emprunts;

    public function __construct()
    {
        $this->emprunts = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        //$this->newPassword = null;
    }

    public function getEtablissement(): ?string
    {
        return $this->etablissement;
    }

    public function setEtablissement(?string $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(?string $classe): self
    {
        $this->classe = $classe;

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

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function setGenre($genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
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

    /**
     * @return Collection|Emprunt[]
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): self
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts[] = $emprunt;
            $emprunt->setUser($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getUser() === $this) {
                $emprunt->setUser(null);
            }
        }

        return $this;
    }
}
