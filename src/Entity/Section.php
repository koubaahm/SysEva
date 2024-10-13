<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
#[Broadcast]
class Section
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'section', targetEntity: Article::class)]
    private Collection $article;

    #[ORM\OneToMany(mappedBy: 'MySection', targetEntity: User::class)]
    private Collection $SectionEditors;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'MySenEdsections')]
    private ?User $seniorEditor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $acronyme = null;

    #[ORM\OneToMany(mappedBy: 'section', targetEntity: Keyword::class, cascade: ['persist', 'remove'])]
    private Collection $keywords;

    public function __construct()
    {
        $this->article = new ArrayCollection();
        $this->SectionEditors = new ArrayCollection();
        $this->keywords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->article->contains($article)) {
            $this->article->add($article);
            $article->setSection($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getSection() === $this) {
                $article->setSection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSectionEditors(): Collection
    {
        return $this->SectionEditors;
    }

    public function addSectionEditor(User $SectionEditor): static
    {
        if (!$this->SectionEditors->contains($SectionEditor)) {
            $this->SectionEditors->add($SectionEditor);
            $SectionEditor->setMySection($this);
        }

        return $this;
    }

    public function removeSectionEditor(User $SectionEditor): static
    {
        if ($this->SectionEditors->removeElement($SectionEditor)) {
            if ($SectionEditor->getMySection() === $this) {
                $SectionEditor->setMySection(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSeniorEditor(): ?User
    {
        return $this->seniorEditor;
    }

    public function setSeniorEditor(?User $seniorEditor): static
    {
        $this->seniorEditor = $seniorEditor;

        return $this;
    }

    public function getAcronyme(): ?string
    {
        return $this->acronyme;
    }

    public function setAcronyme(?string $acronyme): static
    {
        $this->acronyme = $acronyme;

        return $this;
    }

    /**
     * @return Collection<int, Keyword>
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): static
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords->add($keyword);
            $keyword->setSection($this);
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): static
    {
        if ($this->keywords->removeElement($keyword)) {
            // set the owning side to null (unless already changed)
            if ($keyword->getSection() === $this) {
                $keyword->setSection(null);
            }
        }

        return $this;
    }
}