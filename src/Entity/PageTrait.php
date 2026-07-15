<?php

namespace VladX\PagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait PageTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Embedded(class: Meta::class)]
    private MetaInterface $meta;

    public function initMeta(): void
    {
        $this->meta = new Meta();
        $this->meta->initMeta();
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
    public function setMeta(MetaInterface $meta): void
    {
        $this->meta = $meta;
    }
    public function getMeta(): MetaInterface
    {
        return $this->meta;
    }
}
