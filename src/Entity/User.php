<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 30)]
    private ?string $pseudo = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private int $numberOfVotes = 0;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Deal::class)]
    private Collection $deals;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: PromoCode::class)]
    private Collection $promoCodes;

    #[ORM\ManyToMany(targetEntity: Deal::class, inversedBy: 'userSave')]
    private Collection $dealsSave;

    #[ORM\ManyToMany(targetEntity: PromoCode::class, inversedBy: 'usersSave')]
    private Collection $promoCodesSave;

    #[ORM\ManyToMany(targetEntity: Badge::class, mappedBy: 'users')]
    private Collection $badges;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Alert::class)]
    private Collection $alerts;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->deals = new ArrayCollection();
        $this->promoCodes = new ArrayCollection();
        $this->dealsSave = new ArrayCollection();
        $this->promoCodesSave = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->alerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNumberOfVotes(): int
    {
        return $this->numberOfVotes;
    }

    public function setNumberOfVotes(int $nb): self
    {
        $this->numberOfVotes = $nb >= 0 ? $nb : 0;

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
            $comment->setUtilisateur($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUtilisateur() === $this) {
                $comment->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getDeals(): Collection
    {
        return $this->deals;
    }

    public function addDeal(Deal $deal): self
    {
        if (!$this->deals->contains($deal)) {
            $this->deals->add($deal);
            $deal->setAuthor($this);
        }

        return $this;
    }

    public function removeDeal(Deal $deal): self
    {
        if ($this->deals->removeElement($deal)) {
            // set the owning side to null (unless already changed)
            if ($deal->getAuthor() === $this) {
                $deal->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PromoCode>
     */
    public function getPromoCodes(): Collection
    {
        return $this->promoCodes;
    }

    public function addPromoCode(PromoCode $promoCode): self
    {
        if (!$this->promoCodes->contains($promoCode)) {
            $this->promoCodes->add($promoCode);
            $promoCode->setAuthor($this);
        }

        return $this;
    }

    public function removePromoCode(PromoCode $promoCode): self
    {
        if ($this->promoCodes->removeElement($promoCode)) {
            // set the owning side to null (unless already changed)
            if ($promoCode->getAuthor() === $this) {
                $promoCode->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Deal>
     */
    public function getDealsSave(): Collection
    {
        return $this->dealsSave;
    }

    public function addDealsSave(Deal $dealsSave): static
    {
        if (!$this->dealsSave->contains($dealsSave)) {
            $this->dealsSave->add($dealsSave);
        }

        return $this;
    }

    public function removeDealsSave(Deal $dealsSave): static
    {
        $this->dealsSave->removeElement($dealsSave);

        return $this;
    }

    /**
     * @return Collection<int, PromoCode>
     */
    public function getPromoCodesSave(): Collection
    {
        return $this->promoCodesSave;
    }

    public function addPromoCodesSave(PromoCode $promoCodesSave): static
    {
        if (!$this->promoCodesSave->contains($promoCodesSave)) {
            $this->promoCodesSave->add($promoCodesSave);
        }

        return $this;
    }

    public function removePromoCodesSave(PromoCode $promoCodesSave): static
    {
        $this->promoCodesSave->removeElement($promoCodesSave);

        return $this;
    }

    /**
     * @return Collection<int, Badge>
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): static
    {
        if (!$this->badges->contains($badge)) {
            $this->badges->add($badge);
            $badge->addUser($this);
        }

        return $this;
    }

    public function removeBadge(Badge $badge): static
    {
        if ($this->badges->removeElement($badge)) {
            $badge->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Alert>
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): static
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts->add($alert);
            $alert->setUser($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): static
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getUser() === $this) {
                $alert->setUser(null);
            }
        }

        return $this;
    }
}
