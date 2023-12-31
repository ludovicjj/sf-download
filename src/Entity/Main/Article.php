<?php

namespace App\Entity\Main;

use App\Repository\ArticleRepository;
use App\Service\UploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageFilename = null;

    #[ORM\Column(nullable: true)]
    private ?string $excelFilename = null;

    // Can get ArticleReference from Article, but not set/add
    // Must use ArticleReference to set Article to ArticleReference
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleReference::class)]
    private Collection $articleReferences;

    public function __construct()
    {
        $this->articleReferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;
        return $this;
    }

    public function getExcelFilename(): ?string
    {
        return $this->excelFilename;
    }

    public function setExcelFilename(string $excelFilename): self
    {
        $this->excelFilename = $excelFilename;

        return $this;
    }

    public function getImagePath(): string
    {
        return UploaderHelper::ARTICLE_IMAGE_DIR . '/'.$this->getImageFilename();
    }

    /**
     * @return Collection<int, ArticleReference>
     */
    public function getArticleReferences(): Collection
    {
        return $this->articleReferences;
    }
}