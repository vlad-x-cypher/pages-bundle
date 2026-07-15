<?php

namespace VladX\PagesBundle\Entity;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[MappedSuperclass]
#[Vich\Uploadable]
class Page implements PageInterface
{
    use PageTrait;

    #[Vich\UploadableField(mapping: "metaimage", fileNameProperty: "meta.ogImage.name", size: "meta.ogImage.size", mimeType: "meta.ogImage.mimeType", originalName: "meta.ogImage.originalName", dimensions: "meta.ogImage.dimensions")]
    private ?File $metaOgImageFile = null;

    public function __construct()
    {
        $this->initMeta();
    }

    public function setMetaOgImageFile(?File $metaOgImageFile): static
    {
        $this->meta->setOgImageFile($metaOgImageFile);
        return $this;
    }

    public function getMetaOgImageFile(): ?File
    {
        return $this->meta->getOgImageFile();
    }
}
