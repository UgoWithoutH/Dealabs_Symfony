<?php

namespace App\Entity;

use App\Repository\AlertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlertRepository::class)]
class Alert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $keyword = null;

    #[ORM\Column]
    private ?int $minimumTemperature = null;

    #[ORM\ManyToOne(inversedBy: 'alerts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $emailNotification = null;

    #[ORM\ManyToMany(targetEntity: Deal::class)]
    private Collection $deals;

    #[ORM\ManyToMany(targetEntity: PromoCode::class)]
    private Collection $promoCodes;

    public function __construct()
    {
        $this->deals = new ArrayCollection();
        $this->promoCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): static
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getMinimumTemperature(): ?int
    {
        return $this->minimumTemperature;
    }

    public function setMinimumTemperature(int $minimumTemperature): static
    {
        $this->minimumTemperature = $minimumTemperature;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isEmailNotification(): ?bool
    {
        return $this->emailNotification;
    }

    public function setEmailNotification(bool $emailNotification): static
    {
        $this->emailNotification = $emailNotification;

        return $this;
    }

    /**
     * @return Collection<int, Deal>
     */
    public function getDeals(): Collection
    {
        return $this->deals;
    }

    public function addDeal(Deal $deal): static
    {
        if (!$this->deals->contains($deal)) {
            $this->deals->add($deal);
        }

        return $this;
    }

    public function removeDeal(Deal $deal): static
    {
        $this->deals->removeElement($deal);

        return $this;
    }

    /**
     * @return Collection<int, PromoCode>
     */
    public function getPromoCodes(): Collection
    {
        return $this->promoCodes;
    }

    public function addPromoCode(PromoCode $promoCode): static
    {
        if (!$this->promoCodes->contains($promoCode)) {
            $this->promoCodes->add($promoCode);
        }

        return $this;
    }

    public function removePromoCode(PromoCode $promoCode): static
    {
        $this->promoCodes->removeElement($promoCode);

        return $this;
    }
}
