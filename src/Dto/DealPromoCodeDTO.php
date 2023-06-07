<?php

namespace App\Dto;

use App\Entity\Deal;
use App\Entity\PromoCode;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class DealPromoCodeDTO
{
    private ?int $id;
    private ?int $hotLevel;
    private ?\DateTimeInterface $publicationDatetime;
    private ?\DateTimeInterface $expirationDatetime;
    private ?string $link;
    private ?string $title;
    private ?string $description;
    private ?string $promoCode;
    private ?float $price;
    private ?float $usualPrice;
    private ?float $shippingCost;
    private ?bool $freeDelivery;
    private Collection $comments;
    private ?User $author;
    private ?string $groupDeal;
    private ?string $typeOfReduction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotLevel(): ?int
    {
        return $this->hotLevel;
    }

    public function setHotLevel(?int $hotLevel): void
    {
        $this->hotLevel = $hotLevel;
    }

    public function getPublicationDatetime(): ?\DateTimeInterface
    {
        return $this->publicationDatetime;
    }

    public function setPublicationDatetime(?\DateTimeInterface $publicationDatetime): void
    {
        $this->publicationDatetime = $publicationDatetime;
    }

    public function getExpirationDatetime(): ?\DateTimeInterface
    {
        return $this->expirationDatetime;
    }

    public function setExpirationDatetime(?\DateTimeInterface $expirationDatetime): void
    {
        $this->expirationDatetime = $expirationDatetime;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getUsualPrice(): ?float
    {
        return $this->usualPrice;
    }

    public function setUsualPrice(?float $usualPrice): void
    {
        $this->usualPrice = $usualPrice;
    }

    public function getShippingCost(): ?float
    {
        return $this->shippingCost;
    }

    public function setShippingCost(?float $shippingCost): void
    {
        $this->shippingCost = $shippingCost;
    }

    public function getFreeDelivery(): ?bool
    {
        return $this->freeDelivery;
    }

    public function setFreeDelivery(?bool $freeDelivery): void
    {
        $this->freeDelivery = $freeDelivery;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    public function getGroupDeal(): ?string
    {
        return $this->groupDeal;
    }

    public function setGroupDeal(?string $groupDeal): void
    {
        $this->groupDeal = $groupDeal;
    }

    public function getTypeOfReduction(): ?string
    {
        return $this->typeOfReduction;
    }

    public function setTypeOfReduction(?string $typeOfReduction): void
    {
        $this->typeOfReduction = $typeOfReduction;
    }

    public function setDeal(Deal $deal): void
    {
        $this->id = $deal->getId();
        $this->hotLevel = $deal->getHotLevel();
        $this->publicationDatetime = $deal->getPublicationDatetime();
        $this->expirationDatetime = $deal->getExpirationDatetime();
        $this->link = $deal->getLink();
        $this->title = $deal->getTitle();
        $this->description = $deal->getDescription();
        $this->promoCode = $deal->getPromoCode();
        $this->price = $deal->getPrice();
        $this->usualPrice = $deal->getUsualPrice();
        $this->shippingCost = $deal->getShippingCost();
        $this->freeDelivery = $deal->isFreeDelivery();
        $this->comments = $deal->getComments();
        $this->author = $deal->getAuthor();
        $this->groupDeal = $deal->getGroupDeal();
        $this->typeOfReduction = null;
    }

    public function setPromoCode(PromoCode $promoCode): void
    {
        $this->id = $promoCode->getId();
        $this->hotLevel = $promoCode->getHotLevel();
        $this->publicationDatetime = $promoCode->getPublicationDatetime();
        $this->expirationDatetime = $promoCode->getExpirationDatetime();
        $this->link = $promoCode->getLink();
        $this->title = $promoCode->getTitle();
        $this->description = $promoCode->getDescription();
        $this->promoCode = $promoCode->getPromoCodeValue();
        $this->price = null;
        $this->usualPrice = null;
        $this->shippingCost = null;
        $this->freeDelivery = null;
        $this->comments = $promoCode->getComments();
        $this->author = $promoCode->getAuthor();
        $this->groupDeal = $promoCode->getGroupDeal();
        $this->typeOfReduction = $promoCode->getTypeOfReduction();
    }
}
