<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: 'user')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;  // Par défaut, l'utilisateur est actif

    // Ajout de la colonne createdAt
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $createdAt;
     

    // Méthodes UserInterface et PasswordAuthenticatedUserInterface
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function getRoles(): array
    {
        // Garantie qu'un utilisateur a au moins ROLE_USER
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
    
        return array_unique($roles);
    }
    

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function eraseCredentials(): void
    {
        // Si tu stockes des données sensibles temporaires, nettoie-les ici.
    }

    // Getters et setters pour les autres propriétés
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isActive(): bool
{
    return $this->isActive;
}

public function setIsActive(bool $isActive): self
{
    $this->isActive = $isActive;
    return $this;
}
public function __construct()
{
    // Initialisation de createdAt à la date actuelle
    $this->createdAt = new \DateTime();
}

// Getters et Setters pour createdAt
public function getCreatedAt(): \DateTimeInterface
{
    return $this->createdAt;
}

public function setCreatedAt(\DateTimeInterface $createdAt): self
{
    $this->createdAt = $createdAt;

    return $this;
}

}
