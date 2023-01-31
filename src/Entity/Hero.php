<?php

namespace App\Entity;

use App\Repository\HeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroRepository::class)]
class Hero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $hero_name = null;

    #[ORM\OneToOne(inversedBy: 'hero', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $rank = null;

    #[ORM\Column]
    private ?bool $availability = null;

    #[ORM\ManyToMany(targetEntity: Ability::class, inversedBy: 'heroes')]
    private Collection $ability_id;

    #[ORM\OneToMany(mappedBy: 'hero_id', targetEntity: Mission::class)]
    private Collection $missions;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'hero_id')]
    private Collection $events;

    public function __construct()
    {
        $this->ability_id = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeroName(): ?string
    {
        return $this->hero_name;
    }

    public function setHeroName(string $hero_name): self
    {
        $this->hero_name = $hero_name;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function isAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return Collection<int, Ability>
     */
    public function getAbilityId(): Collection
    {
        return $this->ability_id;
    }

    public function addAbilityId(Ability $abilityId): self
    {
        if (!$this->ability_id->contains($abilityId)) {
            $this->ability_id->add($abilityId);
        }

        return $this;
    }

    public function removeAbilityId(Ability $abilityId): self
    {
        $this->ability_id->removeElement($abilityId);

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setHeroId($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getHeroId() === $this) {
                $mission->setHeroId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addHeroId($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeHeroId($this);
        }

        return $this;
    }
}
