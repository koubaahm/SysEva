<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Broadcast]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $pmid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $doi = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteurPrincipale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteurs = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $affiliation = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pdfFileName = null;

    #[ORM\ManyToOne(inversedBy: 'article')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Section $section = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $publicationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $journalName = null;


    #[ORM\OneToMany(mappedBy: 'Article', targetEntity: Evaluation::class, orphanRemoval: true)]
    private Collection $evaluations;

    #[ORM\Column(nullable: true)]
    private ?float $moyenne = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'myArticles')]
    private Collection $invitedUsers;


    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
    }


    public function getPdfFileName(): ?string
    {
        return $this->pdfFileName;
    }

    public function setPdfFileName(?string $pdfFileName): static
    {
        $this->pdfFileName = $pdfFileName;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPmid(): ?int
    {
        return $this->pmid;
    }

    public function setPmid(?int $pmid): static
    {
        $this->pmid = $pmid;

        return $this;
    }

    public function getDoi(): ?string
    {
        return $this->doi;
    }

    public function setDoi(?string $doi): static
    {
        $this->doi = $doi;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteurPrincipale(): ?string
    {
        return $this->auteurPrincipale;
    }

    public function setAuteurPrincipale(?string $auteurPrincipale): static
    {
        $this->auteurPrincipale = $auteurPrincipale;

        return $this;
    }

    public function getAuteurs(): ?string
    {
        return $this->auteurs;
    }

    public function setAuteurs(?string $auteurs): static
    {
        $this->auteurs = $auteurs;

        return $this;
    }

    public function getAffiliation(): ?string
    {
        return $this->affiliation;
    }

    public function setAffiliation(?string $affiliation): static
    {
        $this->affiliation = $affiliation;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): static
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): static
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setArticle($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getArticle() === $this) {
                $evaluation->setArticle(null);
            }
        }

        return $this;
    }

    public function getPublicationDate(): ?string
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?string $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }
    public function getJournalName(): ?string
    {
        return $this->journalName;
    }

    public function setJournalName(?string $journalName): static
    {
        $this->journalName = $journalName;
        return $this;
    }

    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    public function setMoyenne(?float $moyenne): static
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getInvitedUsers(): Collection
    {
        return $this->invitedUsers;
    }

    public function addInvitedUser(User $invitedUser): static
    {
        if (!$this->invitedUsers->contains($invitedUser)) {
            $this->invitedUsers->add($invitedUser);
            $invitedUser->addMyArticle($this);
        }

        return $this;
    }

    public function removeInvitedUser(User $invitedUser): static
    {
        if ($this->invitedUsers->removeElement($invitedUser)) {
            $invitedUser->removeMyArticle($this);
        }

        return $this;
    }

}