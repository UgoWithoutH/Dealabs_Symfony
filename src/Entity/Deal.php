<?php

namespace App\Entity;

use App\Enum\Group;
use App\Enum\TypeOfReduction;
use App\Repository\DealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'promoCode', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'deals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $group = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $typeOfReduction = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

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
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setDeal($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getDeal() === $this) {
                $comment->setDeal(null);
            }
        }

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

    public function setGroup(string $group): void
    {
        $validValues = [
            Group::HIGHTECH,
            Group::EPICERIEETCOURSES,
            Group::MODEETACCESSOIRE,
        ];

        if (!in_array($group, $validValues, true)) {
            throw new \InvalidArgumentException('Invalid group value.');
        }

        $this->group = $group;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setTypeOfReduction(string $typeOfReduction): void
    {
        $validValues = [
           TypeOfReduction::EURO,
            TypeOfReduction::LIVRAISONGRATUITE,
            TypeOfReduction::POURCENTAGE,
        ];

        if (!in_array($typeOfReduction, $validValues, true)) {
            throw new \InvalidArgumentException('Invalid group value.');
        }

        $this->group = $typeOfReduction;
    }

    public function getTypeOfReduction()
    {
        return $this->typeOfReduction;
    }
}
