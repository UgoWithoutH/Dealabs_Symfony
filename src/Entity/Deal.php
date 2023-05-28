<?php

namespace App\Entity;

use App\Repository\DealRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DealRepository::class)]
class Deal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $hotLevel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publicationDatetime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expirationDatetime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $promoCode = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?float $usualPrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $shippingCost = null;

    #[ORM\Column]
    private ?bool $freeDelivery = null;

    #[ORM\ManyToOne(inversedBy: 'deals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotLevel(): ?int
    {
        return $this->hotLevel;
    }

    public function setHotLevel(int $hotLevel): self
    {
        $this->hotLevel = $hotLevel;

        return $this;
    }

    public function getPublicationDatetime(): ?\DateTimeInterface
    {
        return $this->publicationDatetime;
    }

    public function setPublicationDatetime(\DateTimeInterface $publicationDatetime): self
    {
        $this->publicationDatetime = $publicationDatetime;

        return $this;
    }

    public function getExpirationDatetime(): ?\DateTimeInterface
    {
        return $this->expirationDatetime;
    }

    public function setExpirationDatetime(\DateTimeInterface $expirationDatetime): self
    {
        $this->expirationDatetime = $expirationDatetime;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPromoCode(): ?string
    {
        return $this->promoCode;
    }

    public function setPromoCode(?string $promoCode): self
    {
        $this->promoCode = $promoCode;

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

    public function getUsualPrice(): ?float
    {
        return $this->usualPrice;
    }

    public function setUsualPrice(?float $usualPrice): self
    {
        $this->usualPrice = $usualPrice;

        return $this;
    }

    public function getShippingCost(): ?float
    {
        return $this->shippingCost;
    }

    public function setShippingCost(?float $shippingCost): self
    {
        $this->shippingCost = $shippingCost;

        return $this;
    }

    public function isFreeDelivery(): ?bool
    {
        return $this->freeDelivery;
    }

    public function setFreeDelivery(bool $freeDelivery): self
    {
        $this->freeDelivery = $freeDelivery;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }
}
