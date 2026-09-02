<?php

namespace VladX\PagesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use VladX\PagesBundle\Dto\SitemapSettingsDto;
use VladX\PagesBundle\Dto\TemplateDto;

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

    protected ?self $parent = null;

    protected Collection $children;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullSlug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $template = null;

    #[ORM\Column(nullable: true)]
    private ?array $templateData = null;

    #[ORM\Column(nullable: true)]
    private ?array $sitemapConfig = null;

    private ?TemplateDto $virtualTemplate = null;

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

    public function setMeta(MetaInterface $meta): static
    {
        $this->meta = $meta;
        return $this;
    }
    public function getMeta(): MetaInterface
    {
        return $this->meta;
    }

    public function setTemplate(?string $template): static
    {
        $this->template = $template;
        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplateData(?array $templateData): static
    {
        $this->templateData = $templateData;
        return $this;
    }

    public function getTemplateData(): ?array
    {
        return $this->templateData;
    }

    public function setVirtualTemplate(TemplateDto $virtualTemplate): static
    {
        if (!empty($virtualTemplate?->template) && $virtualTemplate?->template == $this->getTemplate()) {
            $this->setTemplateData($virtualTemplate->templateData);
        }
        $this->setTemplate($virtualTemplate?->template ?? null);

        return $this;
    }

    public function getVirtualTemplate(): TemplateDto
    {
        return new TemplateDto(
            template: $this->getTemplate(),
            data: $this->getTemplateData(),
        );
    }

    public function getSitemapConfig(): ?array
    {
        if (!$this->sitemapConfig) {
            return (new SitemapSettingsDto())->toArray();
        }
        return $this->sitemapConfig;
    }

    public function setSitemapConfig(?array $sitemapConfig): static
    {
        $this->sitemapConfig = $sitemapConfig;
        return $this;
    }
}
