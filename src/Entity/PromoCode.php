<?php

namespace App\Entity;

use App\Enum\Group;
use App\Enum\TypeOfReduction;
use App\Repository\PromoCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromoCodeRepository::class)]
class PromoCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
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
    private ?string $promoCodeValue = null;

    #[ORM\OneToMany(mappedBy: 'promoCode', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'promoCodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $groupDeal = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $typeOfReduction = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'promoCodesSave')]
    private Collection $usersSave;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->usersSave = new ArrayCollection();
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
        if ($hotLevel < 0) {
            $this->hotLevel = 0;
        } else {
            $this->hotLevel = $hotLevel;
        }

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

    public function getPromoCodeValue(): ?string
    {
        return $this->promoCodeValue;
    }

    public function setPromoCodeValue(?string $promoCodeValue): self
    {
        $this->promoCodeValue = $promoCodeValue;

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
            $comment->setPromoCode($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPromoCode() === $this) {
                $comment->setPromoCode(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function setGroupDeal(string $groupDeal): void
    {
        $validValues = [
            Group::HIGHTECH,
            Group::EPICERIEETCOURSES,
            Group::MODEETACCESSOIRE,
        ];

        if (!in_array($groupDeal, $validValues, true)) {
            throw new \InvalidArgumentException('Invalid group value.');
        }

        $this->groupDeal = $groupDeal;
    }

    public function getGroupDeal(): ?string
    {
        return $this->groupDeal;
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

        $this->typeOfReduction = $typeOfReduction;
    }

    public function getTypeOfReduction(): ?string
    {
        return $this->typeOfReduction;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersSave(): Collection
    {
        return $this->usersSave;
    }

    public function addUsersSave(User $usersSave): static
    {
        if (!$this->usersSave->contains($usersSave)) {
            $this->usersSave->add($usersSave);
            $usersSave->addPromoCodesSave($this);
        }

        return $this;
    }

    public function removeUsersSave(User $usersSave): static
    {
        if ($this->usersSave->removeElement($usersSave)) {
            $usersSave->removePromoCodesSave($this);
        }

        return $this;
    }
}
