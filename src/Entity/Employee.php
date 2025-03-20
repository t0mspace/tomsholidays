<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = ['ROLE_EMPLOYEE'];

    #[ORM\ManyToOne(inversedBy: 'employees')]
    private ?Department $department = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $manager = null;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'employee')]
    private Collection $requests;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'managedBy')]
    private Collection $manage;

    #[ORM\Column]
    private ?int $nbrOfLegalVacationDaysRemaining = null;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
        $this->manage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

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

    public function getManager(): ?self
    {
        return $this->manager;
    }

    public function setManager(?self $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setEmployee($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        // set the owning side to null (unless already changed)
        if ($this->requests->removeElement($request) && $request->getEmployee() === $this) {
            $request->setEmployee(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getManage(): Collection
    {
        return $this->manage;
    }

    public function addManage(Request $manage): static
    {
        if (!$this->manage->contains($manage)) {
            $this->manage->add($manage);
            $manage->setManagedBy($this);
        }

        return $this;
    }

    public function removeManage(Request $manage): static
    {
        // set the owning side to null (unless already changed)
        if ($this->manage->removeElement($manage) && $manage->getManagedBy() === $this) {
            $manage->setManagedBy(null);
        }

        return $this;
    }

    public function getNbrOfLegalVacationDaysRemaining(): ?int
    {
        return $this->nbrOfLegalVacationDaysRemaining;
    }

    public function setNbrOfLegalVacationDaysRemaining(int $nbrOfLegalVacationDaysRemaining): static
    {
        $this->nbrOfLegalVacationDaysRemaining = $nbrOfLegalVacationDaysRemaining;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = [$roles];
        return $this;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
