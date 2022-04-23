<?php

namespace App\Entity;

use App\Repository\BuzzPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuzzPostRepository::class)]
class BuzzPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'buzzPosts')]
    private $relatedTo;

    #[ORM\OneToMany(mappedBy: 'relatedTo', targetEntity: self::class)]
    private $buzzPosts;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $slug;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'boolean')]
    private $isPublished;

    public function __construct()
    {
        $this->buzzPosts = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
    public function getRelatedTo(): ?self
    {
        return $this->relatedTo;
    }

    public function setRelatedTo(?self $relatedTo): self
    {
        $this->relatedTo = $relatedTo;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getBuzzPosts(): Collection
    {
        return $this->buzzPosts;
    }

    public function addBuzzPost(self $buzzPost): self
    {
        if (!$this->buzzPosts->contains($buzzPost)) {
            $this->buzzPosts[] = $buzzPost;
            $buzzPost->setRelatedTo($this);
        }

        return $this;
    }

    public function removeBuzzPost(self $buzzPost): self
    {
        if ($this->buzzPosts->removeElement($buzzPost)) {
            // set the owning side to null (unless already changed)
            if ($buzzPost->getRelatedTo() === $this) {
                $buzzPost->setRelatedTo(null);
            }
        }

        return $this;
    }
}
