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
    protected ?string $metaTitle = "";

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $metaDescription = "";

    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $metaKeywords = "";

    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $ogTitle = "";

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $ogDescription = "";

    #[ORM\Column(nullable: true)]
    protected ?\DateTimeImmutable $metaUpdatedAt = null;

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
    protected ?EmbeddedFile $ogImage = null;

    #[ORM\Column(type: Types::JSON)]
    protected array $metaProperties = [];

    public function initMeta(): void
    {
        $this->ogImage = new EmbeddedFile();
    }

    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }
    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }
    public function setMetaKeywords(?string $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
    }
    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }
    public function setOgTitle(?string $ogTitle): void
    {
        $this->ogTitle = $ogTitle;
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
    public function setOgImage(?EmbeddedFile $ogImage): void
    {
        $this->ogImage = $ogImage;
    }
    public function getOgImage(): ?EmbeddedFile
    {
        return $this->ogImage;
    }
    public function setOgDescription(?string $ogDescription): void
    {
        $this->ogDescription = $ogDescription;
    }
    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }
    public function setMetaUpdatedAt(?\DateTimeImmutable $metaUpdatedAt): void
    {
        $this->metaUpdatedAt = $metaUpdatedAt;
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
}
