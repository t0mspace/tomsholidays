<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\RequestStatus;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    #[ORM\Column(type: "string", enumType: RequestStatus::class)]
    private ?RequestStatus $status = null;

    #[ORM\ManyToOne(inversedBy: 'manage')]
    private ?Employee $managedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateManaged = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Holiday $holidays = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getStatus(): RequestStatus
    {
        return $this->status;
    }

    public function setStatus(RequestStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getManagedBy(): ?Employee
    {
        return $this->managedBy;
    }

    public function setManagedBy(?Employee $managedBy): static
    {
        $this->managedBy = $managedBy;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDateManaged(): ?\DateTimeImmutable
    {
        return $this->dateManaged;
    }

    public function setDateManaged(?\DateTimeImmutable $dateManaged): static
    {
        $this->dateManaged = $dateManaged;

        return $this;
    }

    public function getHolidays(): ?Holiday
    {
        return $this->holidays;
    }

    public function setHolidays(?Holiday $holidays): static
    {
        $this->holidays = $holidays;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
