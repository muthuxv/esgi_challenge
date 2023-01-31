<?php

namespace App\Entity;

use App\Repository\MissionHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionHistoryRepository::class)]
class MissionHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'missionHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mission $mission = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $update_at = null;

    #[ORM\Column]
    private ?int $update_by = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMissionId(): ?Mission
    {
        return $this->mission;
    }

    public function setMissionId(?Mission $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeImmutable $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getUpdateBy(): ?int
    {
        return $this->update_by;
    }

    public function setUpdateBy(int $update_by): self
    {
        $this->update_by = $update_by;

        return $this;
    }
}
