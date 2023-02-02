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
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Hero::class, inversedBy: 'events')]
    private Collection $heroes;

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
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Hero>
     */
    public function getHeroes(): Collection
    {
        return $this->heroes;
    }

    public function addHero(Hero $hero): self
    {
        if (!$this->heroes->contains($hero)) {
            $this->heroes->add($hero);
        }

        return $this;
    }

    public function removeHero(Hero $hero): self
    {
        $this->heroes->removeElement($hero);

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
            $eventPayment->setEvent($this);
        }

        return $this;
    }

    public function removeEventPayment(EventPayment $eventPayment): self
    {
        if ($this->eventPayments->removeElement($eventPayment)) {
            // set the owning side to null (unless already changed)
            if ($eventPayment->getEvent() === $this) {
                $eventPayment->setEvent(null);
            }
        }

        return $this;
    }
}
