<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'author')]
    private Collection $userTask;

    public function __construct()
    {
        $this->userTask = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getUserTask(): Collection
    {
        return $this->userTask;
    }

    public function addUserTask(Task $userTask): static
    {
        if (!$this->userTask->contains($userTask)) {
            $this->userTask->add($userTask);
            $userTask->setAuthor($this);
        }

        return $this;
    }

    public function removeUserTask(Task $userTask): static
    {
        if ($this->userTask->removeElement($userTask)) {
            // set the owning side to null (unless already changed)
            if ($userTask->getAuthor() === $this) {
                $userTask->setAuthor(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Si tu avais un mot de passe en clair ou d'autres données sensibles temporaires,
        // tu pourrais les supprimer ici.
        // Exemple : $this->plainPassword = null;
    }

}
