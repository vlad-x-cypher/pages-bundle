<?php

namespace VladX\PagesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait PageTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true, nullable: false)]
    private string $slug = "";

    #[ORM\Embedded(class: Meta::class)]
    private MetaInterface $meta;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullSlug = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    public function initMeta(): void
    {
        $this->meta = new Meta();
        $this->meta->initMeta();
    }

    public function initChildren(): void
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    public function getFullSlug(): ?string
    {
        return $this->fullSlug;
    }

    public function computeFullSlug(): void
    {
        $parent = $this->getParent();
        $parentFullSlug = $parent?->getFullSlug() ?? '';
        $slug = $this->getSlug();

        if ($slug === '') {
            $this->fullSlug = null;
            return;
        }

        $this->fullSlug = $parentFullSlug !== '' ? $parentFullSlug . '/' . $slug : $slug;
    }

    public function cascadeFullSlug(): void
    {
        foreach ($this->getChildren() as $child) {
            $child->initMeta();
            $child->computeFullSlug();
            $child->cascadeFullSlug();
        }
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
        return $this;
    }

    public function setMeta(MetaInterface $meta): void
    {
        $this->meta = $meta;
    }
    public function getMeta(): MetaInterface
    {
        return $this->meta;
    }
}
