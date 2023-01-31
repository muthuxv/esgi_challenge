<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $user_id;

    #[ORM\ManyToMany(targetEntity: Hero::class, inversedBy: 'events')]
    private Collection $hero_id;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventPayment::class, orphanRemoval: true)]
    private Collection $eventPayments;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->hero_id = new ArrayCollection();
        $this->eventPayments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        $this->user_id->removeElement($userId);

        return $this;
    }

    /**
     * @return Collection<int, Hero>
     */
    public function getHeroId(): Collection
    {
        return $this->hero_id;
    }

    public function addHeroId(Hero $heroId): self
    {
        if (!$this->hero_id->contains($heroId)) {
            $this->hero_id->add($heroId);
        }

        return $this;
    }

    public function removeHeroId(Hero $heroId): self
    {
        $this->hero_id->removeElement($heroId);

        return $this;
    }

    /**
     * @return Collection<int, EventPayment>
     */
    public function getEventPayments(): Collection
    {
        return $this->eventPayments;
    }

    public function addEventPayment(EventPayment $eventPayment): self
    {
        if (!$this->eventPayments->contains($eventPayment)) {
            $this->eventPayments->add($eventPayment);
            $eventPayment->setEventId($this);
        }

        return $this;
    }

    public function removeEventPayment(EventPayment $eventPayment): self
    {
        if ($this->eventPayments->removeElement($eventPayment)) {
            // set the owning side to null (unless already changed)
            if ($eventPayment->getEventId() === $this) {
                $eventPayment->setEventId(null);
            }
        }

        return $this;
    }
}
