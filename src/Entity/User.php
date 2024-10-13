<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 15)]
    private ?string $numTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $laboratoire = null;

    #[ORM\Column]
    private ?bool $etat = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $confirmationToken = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Evaluation::class, orphanRemoval: true)]
    private Collection $evaluations;

    #[ORM\Column(nullable: true)]
    private ?array $competences = null;

    #[ORM\ManyToOne(targetEntity: Section::class, inversedBy: 'SectionEditors')]
    #[ORM\JoinColumn(name: 'section_id', referencedColumnName: 'id')]
    private ?Section $MySection = null;

    #[ORM\OneToMany(mappedBy: 'seniorEditor', targetEntity: Section::class)]
    private Collection $MySenEdsections;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ResetPasswordRequest::class, cascade: ['remove'])]
    private Collection $resetPasswordRequests;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, cascade: ['remove'])]
    private Collection $notifications;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'invitedUsers')]
    private Collection $myArticles;
     
    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
        $this->MySenEdsections = new ArrayCollection();
        $this->resetPasswordRequests = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->myArticles = new ArrayCollection();
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

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): self
    {
        $this->numTel = $numTel;
        return $this;
    }

    public function getLaboratoire(): ?string
    {
        return $this->laboratoire;
    }

    public function setLaboratoire(?string $laboratoire): self
    {
        $this->laboratoire = $laboratoire;
        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }


    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }
        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }
        return $this;
    }
    public function getCompetences(): ?array
    {
        return $this->competences;
    }

    public function setCompetences(?array $competences): static
    {
        $this->competences = $competences;
        return $this;
    }
    public function getMySection(): ?Section
    {
        return $this->MySection;
    }

    public function setMySection(?Section $MySection): static
    {
        $this->MySection = $MySection;
        return $this;
    }
    /**
     * @return Collection<int, Section>
     */
    public function getMySenEdsections(): Collection
    {
        return $this->MySenEdsections;
    }

    public function addMySenEdsection(Section $mySenEdsection): static
    {
        if (!$this->MySenEdsections->contains($mySenEdsection)) {
            $this->MySenEdsections->add($mySenEdsection);
            $mySenEdsection->setSeniorEditor($this);
        }

        return $this;
    }

    public function removeMySenEdsection(Section $mySenEdsection): static
    {
        if ($this->MySenEdsections->removeElement($mySenEdsection)) {
            // set the owning side to null (unless already changed)
            if ($mySenEdsection->getSeniorEditor() === $this) {
                $mySenEdsection->setSeniorEditor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getMyArticles(): Collection
    {
        return $this->myArticles;
    }

    public function addMyArticle(Article $myArticle): static
    {
        if (!$this->myArticles->contains($myArticle)) {
            $this->myArticles->add($myArticle);
        }

        return $this;
    }

    public function removeMyArticle(Article $myArticle): static
    {
        $this->myArticles->removeElement($myArticle);

        return $this;
    }
}
