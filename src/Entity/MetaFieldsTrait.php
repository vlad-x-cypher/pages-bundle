<?php

namespace VladX\PagesBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Attribute as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

trait MetaFieldsTrait
{
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $metaTitle = "";

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $metaDescription = "";

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $metaKeywords = "";

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $ogTitle = "";

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ogDescription = "";

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $metaUpdatedAt = null;

    #[Vich\UploadableField(
        mapping: "metaimage",
        fileNameProperty: "ogImage.name",
        size: "ogImage.size",
        mimeType: "ogImage.mimeType",
        originalName: "ogImage.originalName",
        dimensions: "ogImage.dimensions"
    )]
    #[Assert\File(
        maxSize: "5M",
        mimeTypes: ['image/jpeg', 'image/jpg', 'image/png'],
        mimeTypesMessage: "Upload valid mime",
    )]
    private ?File $ogImageFile = null;

    #[ORM\Embedded(class: EmbeddedFile::class)]
    private ?EmbeddedFile $ogImage = null;

    #[ORM\Column(type: Types::JSON)]
    private array $metaProperties = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $robots = [];

    public function initMeta(): void
    {
        $this->ogImage = new EmbeddedFile();
    }

    public function setMetaTitle(?string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }
    public function setMetaDescription(?string $metaDescription): static
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }
    public function setMetaKeywords(?string $metaKeywords): static
    {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }
    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }
    public function setOgTitle(?string $ogTitle): static
    {
        $this->ogTitle = $ogTitle;
        return $this;
    }
    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }
    public function setOgImageFile(?File $ogImageFile): static
    {
        $this->ogImageFile = $ogImageFile;
        if (null !== $ogImageFile) {
            $this->setMetaUpdatedAt(new \DateTimeImmutable());
        }
        return $this;
    }
    public function getOgImageFile(): ?File
    {
        return $this->ogImageFile;
    }
    public function setOgImage(?EmbeddedFile $ogImage): static
    {
        $this->ogImage = $ogImage;
        return $this;
    }
    public function getOgImage(): ?EmbeddedFile
    {
        return $this->ogImage;
    }
    public function setOgDescription(?string $ogDescription): static
    {
        $this->ogDescription = $ogDescription;
        return $this;
    }
    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }
    public function setMetaUpdatedAt(?\DateTimeImmutable $metaUpdatedAt): static
    {
        $this->metaUpdatedAt = $metaUpdatedAt;
        return $this;
    }
    public function getMetaUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->metaUpdatedAt;
    }
    /** @param array<int, array{property: string, content: string}> $metaProperties */
    public function setMetaProperties(array $metaProperties = []): static
    {
        $this->metaProperties = $metaProperties;
        return $this;
    }
    /** @return array<int, array{property: string, content: string}> */
    public function getMetaProperties(): array
    {
        return $this->metaProperties;
    }
    public function getRobots(): ?array
    {
        return $this->robots;
    }
    public function setRobots(?array $robots): static
    {
        $this->robots = $robots;
        return $this;
    }
}
